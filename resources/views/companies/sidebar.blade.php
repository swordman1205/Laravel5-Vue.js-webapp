<bs-drawer
        ref="drawer"
        side="left"
        bg="white"
        sidebar="md"
        class="m-t-65 pt-4 pt-sm-0 col-10 col-md-3 col-lg-2 bs-drawer__animate">
    <div class="hv--100 border-right sidebar">
        <b-button-group vertical class="w--90">
            <b-button active class="sidebar-item w-100 p-3 text-right text-truncate" variant="link"
                      :class="{'font-color-orange': sidebarIndex === 0}" @click="goTo(0)">
                <div class="float-left" v-show="sidebarIndex === 0">
                    <i class="fas fa-chevron-right"></i>
                </div>
                <span class="text-uppercase"> miei corsi</span>
            </b-button>
            <b-button class="sidebar-item w-100 p-3 text-right text-truncate border-top" variant="link"
                      :class="{'font-color-orange': sidebarIndex === 1}" @click="goTo(1)">
                <div class="float-left" v-show="sidebarIndex === 1">
                    <i class="fas fa-chevron-right"></i>
                </div>
                <span class="text-uppercase">Prenotazioni</span>
            </b-button>
            <b-button class="sidebar-item w-100 pt-3 pl-3 pr-3 text-right text-truncate border-top" variant="link"
                      :class="{'font-color-orange': sidebarIndex === 2, 'pb-3' :sidebarIndex !== 2}" @click="goTo(2)">
                <div class="float-left" v-show="sidebarIndex === 2">
                    <i class="fas fa-chevron-right"></i>
                </div>
                <span class="ml-auto text-uppercase">Profilo Società</span>
            </b-button>
            <div v-show="sidebarIndex === 2"></div>
            <b-button class="sidebar-item w-100 p-3 text-right border-top text-truncate" variant="link"
                      :class="{'font-color-orange': sidebarIndex === 3}" @click="goTo(3)">
                <div class="float-left" v-show="sidebarIndex === 3">
                    <i class="float-left fas fa-chevron-right"></i>
                </div>
                <span class="ml-auto text-uppercase mb-1">Account Utente</span>
            </b-button>
            <b-button class="sidebar-item w-100 p-3 text-right border-top text-truncate" variant="link"
                      :class="{'font-color-orange': sidebarIndex === 4}" @click="goTo(4)">
                <div class="float-left" v-show="sidebarIndex === 4">
                    <i class="float-left fas fa-chevron-right"></i>
                </div>
                <span class="ml-auto text-uppercase mb-1">Acquisti</span>
            </b-button>
        </b-button-group>
    </div>
</bs-drawer>