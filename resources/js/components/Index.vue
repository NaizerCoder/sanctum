<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">МЕНЮ</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <router-link :to="{name:'get.index'}" class="nav-link active" aria-current="page">GET</router-link>
                        </li>

                        <li class="nav-item">
                            <router-link :to="{name:'user.login'}" class="nav-link active" aria-current="page">LOGIN</router-link>
                        </li>

                        <li class="nav-item">
                            <router-link :to="{name:'user.registration'}" class="nav-link active" aria-current="page">REGISTRATION</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="p-4">
            <router-view :key="$route.name"></router-view>
        </div>
    </div>

</template>

<script>
export default {
    name: "Index"
}
</script>

<style scoped>

</style>
