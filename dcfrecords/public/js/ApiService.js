(function($, document, window){
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var ApiService = {
	base: 'http://localhost:8888/api/',
	init: function(){
		this.user_guid = this.getCookie('user_guid');
	},
	getRecords: function(args){
		if(args.offset === undefined){
			args.offset = 0;
		}
		if(args.limit === undefined){
			args.limit = 10;
		}
		if(args.sort === undefined){
			args.sort = '-date';
		}

		return this.post('records', args);
	},
	getRecord: function(id_record){
		return this.post('record', {id_record: id_record});
	},
	saveRecord: function(id_record, fields){
		return this.post('record/save', {id_record: id_record, fields: fields});
	},
	post: function(url, post, callback){
		return $.post(this.base + url, $.extend({}, {user_guid: this.user_guid}, post), callback, 'json');
	},

	getContact: function(id_contact, id_record){
		return this.post('contact', {id_contact: id_contact, id_record: id_record});
	},
	saveContact: function(id_contact, id_record, fields){
		return this.post('contact/save', {id_contact: id_contact, id_record: id_record, fields: fields});
	},
	deleteContact: function(id_contact, id_record){
		return this.post('contact/delete', {id_contact: id_contact, id_record: id_record});
	},
	deleteEvent: function(id_record, id_event){
		return this.post('event/delete', {id_event: id_event, id_record: id_record});
	},
	updateContactStatus: function(id_contact, id_record, status){
		return this.post('contact/update-status', {id_contact: id_contact, id_record: id_record, status: status});
	},
	userLogin: function(username, password){
		return this.post('user/login', {username: username, password: password});
	},
	getUser: function(){
		if(!this.user_guid){
			this.user_guid = this.getCookie('user_guid');
		}
		return this.post('user/get', {user_guid: this.user_guid});
	},
	createUser: function(fields){
		return this.post('user/create', fields);
	},
	getUsername: function(first_name, last_name){
		return this.post('user/username', {first_name: first_name, last_name: last_name});
	},
	checkUsername: function(username){
		return this.post('user/username-available', {username: username});
	},
	checkUsernameAndEmail: function(first_name, last_name, email){
		return this.post('user/username-email', {first_name: first_name, last_name: last_name, email: email});
	},
	resetPassword: function(username, email){
		return this.post('user/reset-password', {username: username, email: email});
	},
	userSave: function(fields){
		return this.post('user/save', {fields: fields});
	},
	statusColor: function(status){
		var color;
		if(status == 'created'){
			color = 'default';
		}else if(status == 'pending' || status == 'contact_pending'){
			color = 'info';
		}else if(status == 'contacted'){
			color = 'primary';
		}else if(status == 'complete' || status == 'contact_success'){
			color = 'success';
		}else if(status == 'failed' || status == 'contact_failed'){
			color = 'danger';
		}
		return 'status-' + color;
	},
	contactTypes: function(){
		return [
			{name: 'Phone', value: 'phone'},
			{name: 'Address', value: 'address'},
			{name: 'Facebook', value: 'facebook'},
			{name: 'Note', value: 'note'},
		];
	},
	contactTypeIcons: function(){
		return [
			{icon: 'fa fa-phone', value: 'phone'},
			{icon: 'fa fa-map-marker', value: 'address'},
			{icon: 'fa fa-facebook-square', value: 'facebook'},
			{icon: 'fa fa-sticky-note', value: 'note'},
		];
	},
	contactTypeIconsMap: false,
	contactIcon: function(type){
		if(this.contactTypeIconsMap === false){
			var map = {}, icons = this.contactTypeIcons();
			for(var i = 0; i < icons.length; i++){
				map[ icons[i].value ] = icons[i].icon;
			}

			this.contactTypeIconsMap = map;
		}

		return this.contactTypeIconsMap[type] !== undefined ? this.contactTypeIconsMap[type] : 'icon-not-found';
	},
	contactStatuses: function(){
		return [
			{name: 'Created', value: 'created'},
			{name: 'Contact Pending', value: 'contact_pending'},
			{name: 'Contact Success', value: 'contact_success'},
			{name: 'Contact Failed', value: 'contact_failed'},
			{name: 'Complete', value: 'complete'},
			{name: 'Failed', value: 'failed'},
		];
	},
	contactStatusTextMap: false,
	contactStatusText: function(status){
		if(this.contactStatusTextMap === false){
			var map = {}, statuses = this.contactStatuses();
			for(var i = 0; i < statuses.length; i++){
				map[statuses[i].value] = statuses[i].name;
			}
			this.contactStatusTextMap = map;
		}
		
		return this.contactStatusTextMap[status] !== undefined ? this.contactStatusTextMap[status] : status;
	},
	timeElapsed: function(time, now){
		var breakdown = this.timeElapsedBreakdown(time, now);
		var periodMap = {
			day: 'Day',
			hour: 'Hour',
			minute: 'Minute',
			second: 'Second',
		};
		var str = "", found = 0;

		if(breakdown.diff >= 0 && breakdown.diff <= 5){
			return "Now";
		}

		for(var i = 0; i < breakdown.periods.length; i++){
			var row = breakdown.periods[i];
			if(row.x > 0){
				if(row.period == 'day' && row.x > 7){
					var weeks = Math.floor(row.x / 7), days = row.x -(weeks * 7) ;
					str = weeks + ' Week' + (weeks > 1 ? 's' : '') + " " + days + " Day" + (days > 1 ? 's' : '');
					break;
				}
				if(row.x < 1){
					continue;
				}
				str += (str.length ? " " : "") + row.x + ' ' + periodMap[row.period] + (row.x > 1 ? 's' : '');
				found++;

				if(found > 1){
					break;
				}

			}
		}
		return str ? str : 'N/A';
	},
	timeElapsedBreakdown: function(date, end_date){
		if(end_date === undefined){
			end_date = new Date;
		}else{
			end_date = new Date(end_date);
		}	
		
		var start_date = Api.dateFromUTC(date);

		var start_time = start_date.getTime(), 
			end_time = end_date.getTime(), 
			diff = (end_time - start_time) / 1000;
		
		// Calculate the number of days left
        var days = Math.floor(diff / 86400);
        // After deducting the days calculate the number of hours left
        var hours = Math.floor((diff - (days * 86400 ))/3600)
        // After days and hours , how many minutes are left
        var minutes = Math.floor((diff - (days * 86400 ) - (hours *3600 ))/60)
        // Finally how many seconds left after removing days, hours and minutes.
        var seconds = Math.floor((diff - (days * 86400 ) - (hours *3600 ) - (minutes*60)))

        return {
        	start_date: start_date,
        	end_date: end_date,
        	diff: diff,
        	periods: [
        		{period: 'day', x : days},
	        	{period: 'hour', x : hours},
	        	{period: 'minute', x : minutes},
	        	{period: 'second', x: seconds}
        	],
        };
	},
	dateFromUTC: function(date_str){
		var date = new Date(date_str);
		date.setMinutes( date.getMinutes() - date.getTimezoneOffset() );
		return date;
	},
	datetime: function(date_str){
		return this.formattedDate(date_str, 'datetime');
	},
	date: function(date_str){
		return this.formattedDate(date_str, 'date');
	},
	time: function(date_str){
		return this.formattedDate(date_str, 'time');
	},
	formattedDate: function(date_str, format){
		var date = this.dateFromUTC(date_str);

		var weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
		var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

		var ampm = function(hours){
			var suffix = 'AM';
			if(hours >= 12){
				suffix = 'PM';
			}
			return suffix;
		};
		var hours = function(hours){
			if(hours > 12){
				hours -= 12;
			}
			if(hours < 1){
				hours = 12;
			}
			return hours;
		};
		var minutes = function(minutes){
			if(minutes < 10){
				minutes = '0' + parseInt(minutes);
			}
			return minutes;
		}
		var seconds = function(seconds){
			if(seconds < 10){
				seconds = '0' + parseInt(seconds);
			}
			return seconds;
		}

		var monthday = function(day){
			if(day < 10){
				day = '0' + parseInt(day);
			}
			return day;
		};

		if(format == 'datetime'){
			return [
				weekdays[ date.getDay() ] + ",",
				months[ date.getMonth() ],
				date.getDate(),
				date.getFullYear(),
				'at',
				hours(date.getHours()) + ':' + minutes(date.getMinutes()) + ampm(date.getHours()),
			].join(' ');
		}else if(format == 'date'){
			return [
				weekdays[ date.getDay() ] + ",",
				months[ date.getMonth() ],
				date.getDate(),
				date.getFullYear(),
			].join(' ');
		}else if(format == 'time'){
			return hours(date.getHours()) + ':' + minutes(date.getMinutes()) + ampm(date.getHours());
		}
	},
	getCookie: function(cname){
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length,c.length);
            }
        }
        return "";
    },
    setCookie: function(cname, cvalue, exdays) {
    	if(!exdays){
    		exdays = 1;
    	}
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    },
    deleteCookie: function(cname){
    	this.setCookie(cname, undefined, -10);
    }

};
window.Api = ApiService;
})(jQuery, document, window);

