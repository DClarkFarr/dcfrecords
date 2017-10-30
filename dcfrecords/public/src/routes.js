import Home from './pages/Home.vue';
import RecordEdit from './pages/RecordEdit.vue';
import RecordProfile from './pages/RecordProfile.vue';
import ContactEdit from './pages/ContactEdit.vue';
import NotFound from './pages/NotFound.vue';

export default [
    { path: '/', name: 'Home', component: Home, meta: {title: 'Home'} },
    { path: '/record/edit/:id?', name: 'RecordEdit', component: RecordEdit, meta: {title: 'Create/Edit Record'} },
    { path: '/record/:id?', name: 'RecordProfile', component: RecordProfile, meta: {title: 'Record Profile'} },
    { path: '/record/:record/contact/:id?', component: ContactEdit, meta: {title: "Create/Edit Record Contact"}},
    { path: '/page-not-found', name: 'not-found', component: NotFound },
];
