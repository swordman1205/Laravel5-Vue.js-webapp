<div>
    <b-container>
        <b-row class="w-100">
            <b-col>
                <h2 class="font-weight-bold">Account Utente</h2>
            </b-col>
        </b-row>
        <b-row class="mt-4">
            <b-col class="border-left border-right border-top pt-3 pb-2 pl-5"><h4>Dati dell’account</h4></b-col>
        </b-row>
        <b-row class="border pt-2 pb-2">
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="first-name"
                                                                        class="pl-2 font-weight-bold mt-2">Nome</label>
                </b-col>
                <b-col cols="12" md="6"><input class="form-control form-input mb-2"
                                       :class="{'is-danger': validationErrors.firstName}"
                                       id="first-name"
                                       name="first name"
                                       v-model="user.firstName"/>
                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="last-name"
                                                                        class="pl-2 font-weight-bold mt-2">Cognome</label>
                </b-col>
                <b-col cols="12" md="6"><input class="form-control form-input mb-2"
                                       :class="{'is-danger': validationErrors.lastName}"
                                       id="last-name"
                                       name="last name"
                                       v-model="user.lastName"/>

                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="password"
                                                                        class="pl-2 font-weight-bold mt-2">Nato il</label>
                </b-col>
                <b-col cols="12" md="6"><input class="form-control form-input mb-2"
                                       type="date"
                                       id="birthday"
                                       name="birthday"
                                       min="1900-01-01"
                                       max="2018-01-01"
                                       v-model="user.birthday"/>
                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="email" class="pl-2 font-weight-bold mt-2">Email</label>
                </b-col>
                <b-col cols="12" md="6"><input disabled
                                       class="form-control form-input mb-2"
                                       type="email"
                                       id="email"
                                       name="email"
                                       v-model="user.email"/>

                    <br>
                    <b-button class="mt-2 mb-2 registration-navbar--button font-weight-bold text-uppercase"
                              style="width: 175px;" @click="modifyEmail">
                        Modifica email
                    </b-button>
                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="password"
                                                                        class="pl-2 font-weight-bold mt-2">Password</label>
                </b-col>
                <b-col cols="12" md="6"><input disabled
                                       class="form-control form-input mb-2"
                                       type="password"
                                       id="password"
                                       name="password"
                                       v-model="user.password"/>

                    <br>
                    <b-button class="mt-2 registration-navbar--button font-weight-bold text-uppercase"
                              style="width: 175px;" @click="modifyPassword">
                        Modifica password
                    </b-button>
                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
        </b-row>
        <b-row class="mt-5 mb-5">
            <b-button class="registration-navbar--button font-weight-bold" style="width: 175px;" @click="postForm">
                <span v-if="message">@{{ message }}</span>
                <span v-else>SALVA</span>
            </b-button>
        </b-row>
    </b-container>
</div>