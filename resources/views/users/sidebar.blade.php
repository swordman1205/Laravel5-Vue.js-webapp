<bs-drawer
        side="left"
        bg="transparent"
        sidebar="md"
        class="col-10 col-sm-3 col-md-3 col-lg-2 bs-drawer__animate">
    <div class="hv--100 border-right sidebar">
        <b-button-group vertical class="w--90">
            <b-button class="sidebar-item w-100 p-3 text-right  border-top" variant="link"
                      :class="{'font-color-orange': sidebarIndex === 0}" @click="goTo(0)">
                <div class="float-left" v-show="sidebarIndex === 0">
                    <i class="fas fa-chevron-right"></i>
                </div>
                <span class="text-uppercase">Prenotazioni</span>
            </b-button>
            <b-button class="sidebar-item w-100 p-3 text-right border-top" variant="link"
                      :class="{'font-color-orange': sidebarIndex === 1}" @click="goTo(1)">
                <div class="float-left" v-show="sidebarIndex === 1">
                    <i class="float-left fas fa-chevron-right"></i>
                </div>
                <span class="ml-auto text-uppercase mb-1">Account Utente</span>
            </b-button>
            <b-button class="sidebar-item w-100 p-3 text-right border-top" variant="link"
                      :class="{'font-color-orange': sidebarIndex === 4}" @click="goTo(4)">
                <div class="float-left" v-show="sidebarIndex === 4">
                    <i class="float-left fas fa-chevron-right"></i>
                </div>
                <span class="ml-auto text-uppercase mb-1">Acquisti</span>
            </b-button>
        </b-button-group>
    </div>
</bs-drawer>