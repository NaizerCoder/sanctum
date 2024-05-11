import * as VueRouter from "vue-router";

const route = VueRouter.createRouter ({

    history: VueRouter.createWebHistory(),
    routes: [
        {
            path:'/get',
            component: () => import('./components/Get.vue'),
            name:'get.index'
        },
        {
            path:'/login',
            component: () => import('./components/Login.vue'),
            name:'user.login'
        },
        {
            path:'/registration',
            component: () => import('./components/Registration.vue'),
            name:'user.registration'
        },
        {
            path:'/personal',
            component: () => import('./components/Personal.vue'),
            name:'user.personal'
        },
        {
            path:'/dropzone', component: () => import('./components/Dropzone.vue'),
            name:'dropzone.index'
        },
        {
            path:'/:catchAll(.*)', component: () => import('./components/Personal.vue'),
            name:'404'
        },

    ],

})

 route.beforeEach((to, from, next)=>{

    const token = localStorage.getItem('x-xsrf-token')

    if(!token){
        if( to.name === 'user.login' || to.name === 'user.registration'){
            return next()
        }
        else return next({
            name:'user.login'
        })
    }

    if(token){

        if(to.name ==='user.login' || to.name === 'user.registration'){
            return next({
                name:'user.personal'
            })
        }
    }
    next()

 })


export default route
