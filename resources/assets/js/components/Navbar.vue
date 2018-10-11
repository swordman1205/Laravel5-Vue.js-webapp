<template>
    <nav class="navbar navbar-expand-lg navbar-light mb-4 fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand w-sm-init" href="/"><img src="/images/2.svg" alt="Orangogo Logo"></a>
            <div v-if="title">
                <div class="registration-navbar--vertical-line d-none d-sm-inline-block"></div>
                <b-dropdown class="navbar-dropdown" :text="currentcompany.name">
                    <b-dropdown-item class="navbar-dropdown__item" v-for="(company, index) in companies"
                                     :key="index" :href="dashboardLink(company)">
                        {{company.name}}
                    </b-dropdown-item>
                </b-dropdown>
            </div>

            <div>
                <button class="navbar-toggler" data-toggle="collapse" data-target="#homePageSearchBar"
                        aria-controls="homePageSearchBar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="fa fa-1x fa-search"></span>
                </button>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="homePageSearchBar">

                <navbar-home-sport-search url="/corsi/search"></navbar-home-sport-search>
            </div>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item" :class="nav_active == 0 ? 'active' : ''">
                    </li>
                    <li v-if="user.id" class="nav-item" :class="nav_active == 1 ? 'active' : ''">
                        <a class="nav-link" href="/societa-sportive">Aggiungi Società Sportiva</a>
                    </li>
                    <li v-else class="nav-item" :class="nav_active == 1 ? 'active' : ''">
                        <a class="nav-link" href="https://landing.orangogo.it/insertsocmain/">Aggiungi Società Sportiva</a>
                    </li>
                    <li class="nav-item" :class="nav_active == 2 ? 'active' : ''"
                        v-if="(!authenticated && route!=='register')">
                        <b-button variant="link" class="nav-link text-uppercase"
                                  @click="$store.dispatch('onShowRegister')">
                            registrati
                        </b-button>
                    </li>
                    <li class="nav-item--divider"></li>
                    <li class="nav-item" :class="nav_active == 3 ? 'active' : ''"
                        v-if="(!authenticated && route!=='login')">
                        <b-button variant="link" class="nav-link text-uppercase"
                                  @click="$store.dispatch('onShowLogin')">
                            login
                        </b-button>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-icon" href="/carts"><i class="fas fa-shopping-cart"></i></a>
                    </li>

                    <li class="nav-item nav-item--user" v-if="authenticated">
                        <b-dropdown variant="link" no-caret>
                            <template slot="button-content">
                                <a class="nav-link nav-icon" href="#"><i class="far fa-user"></i></a>
                            </template>

                            <template v-if="hasNotCompanies()">
                                <b-dropdown-item v-if="authenticatedUser.last_name" href="/profile">
                                    {{ authenticatedUser.first_name }} {{ authenticatedUser.last_name }}
                                </b-dropdown-item>
                                <b-dropdown-item v-else>
                                    {{ authenticatedUser.email }}
                                </b-dropdown-item>
                                <b-dropdown-divider></b-dropdown-divider>
                            </template>
                            <template v-if="!hasNotCompanies()">
                                <b-dropdown-item v-for="(company,index) in companies" :key="index"
                                                 :href="dashboardLink(company)">
                                    {{company.name}}
                                </b-dropdown-item>
                                <b-dropdown-divider></b-dropdown-divider>
                            </template>


                            <b-dropdown-item @click="doLogout">Logout</b-dropdown-item>
                        </b-dropdown>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    import NavbarHomeSportSearch from './home/NavbarHomeSportSearch';

    export default {
        name: "Navbar",
        components: {
            NavbarHomeSportSearch,
        },
        props: {
            currentcompany: null,
            title: null,
            companies: {},
            isGuest: {
                type: String,
                required: true
            },
            user: {},
            route: '',
            nav_activeprop: null
        },
        data() {
            return {
                nav_active: 1
            }
        },
        mounted() {
            this.nav_active = this.nav_activeprop;
        },
        computed: {
            authenticated() {
                return this.isGuest !== '1';
            },
            authenticatedUser() {
                return this.user ? this.user : {};
            }
        },
        methods: {
            dashboardLink(company) {
                return `/societa_sportive/${company.slug}/dashboard`;
            },
            hasNotCompanies() {
                if (this.companies)
                    return this.companies.length === 0;
                return true;
            },
            doLogout() {
                axios({
                    method: 'post',
                    url: `${window.baseUrl}/logout`
                }).then(() => {
                    window.location.reload();
                });
            }
        }
    }
</script>