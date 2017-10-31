import Home from './pages/Home.vue';
import RecordEdit from './pages/RecordEdit.vue';
import RecordProfile from './pages/RecordProfile.vue';
import ContactEdit from './pages/ContactEdit.vue';
import Login from './pages/Login.vue';
import LoginCreate from './pages/LoginCreate.vue';
import LoginForgot from './pages/LoginForgot.vue';
import NotFound from './pages/NotFound.vue';

export default [
    { path: '/', name: 'Home', component: Home, meta: {title: 'Home'} },
    { path: '/record/edit/:id?', name: 'RecordEdit', component: RecordEdit, meta: {title: 'Create/Edit Record'} },
    { path: '/record/:id?', name: 'RecordProfile', component: RecordProfile, meta: {title: 'Record Profile'} },
    { path: '/record/:record/contact/:id?', name: 'ContactEdit', component: ContactEdit, meta: {title: "Create/Edit Record Contact"}},
    { path: '/login', name: 'Login', component: Login, meta: {title: "Login to DCF Records"}},
    { path: '/login/forgot', name: 'LoginForgot', component: LoginForgot, meta: {title: "Forgot Password"}},
    { path: '/login/create', name: 'LoginCreate', component: LoginCreate, meta: {title: "Create Account"}},
    { path: '/page-not-found', name: 'not-found', component: NotFound },
];
