<div>
    <b-container>
        <b-row class="w-100">
            <b-col>
                <h2 class="font-weight-bold">Password</h2>
            </b-col>
        </b-row>
        <b-row class="mt-4">
            <b-col class="border-left border-right border-top pt-3 pb-2 pl-5"><h4>Modifica password</h4></b-col>
        </b-row>
        <b-row class="border pt-2 pb-2">
            <b-row class="pt-2 w-100">
                <b-col cols="3" offset-md="1" class="text-right"><label for="current-password"
                                                                        class="pl-2 font-weight-bold mt-2">Password
                        attuale</label>
                </b-col>
                <b-col cols="6"><input class="form-control form-input mb-2"
                                       :class="{'is-danger': validationErrors.currentPassword}"
                                       type="password"
                                       id="current-password"
                                       name="current password"
                                       v-model="currentPassword"/>
                    <p class="text-danger" v-if="validationErrors.currentPassword">@{{
                        validationErrors.currentPassword[0] }}</p>
                </b-col>
                <b-col cols="2"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="3" offset-md="1" class="text-right"><label for="new password"
                                                                        class="pl-2 font-weight-bold mt-2">Password
                        nuova</label>
                </b-col>
                <b-col cols="6"><input class="form-control form-input mb-2"
                                       :class="{'is-danger': validationErrors.newPassword}"
                                       type="password"
                                       id="new password"
                                       name="new password"
                                       v-model="newPassword"/>
                    <p class="text-danger" v-if="validationErrors.newPassword">@{{ validationErrors.newPassword[0]
                        }}</p>

                </b-col>
                <b-col cols="2"></b-col>
            </b-row>
        </b-row>
        <b-row class="mt-5 mb-5">
            <b-button class="registration-navbar--button font-weight-bold" style="width: 175px;" @click="postForm">
                SALVA
            </b-button>
        </b-row>
    </b-container>
</div>