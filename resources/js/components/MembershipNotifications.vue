<template>
<li class="nav-item dropdown notifications m-0">
      <a class="nav-link nav-link-icon text-center" href="#" role="button" id="dropdownEmergencyNotifications" data-toggle='dropdown' aria-haspopup="true" aria-expanded="false">
          <div class="nav-link-icon__wrapper">
              <i class="fas fa-user-plus"></i>
              <span class="badge badge-pill badge-danger" v-if="membership_unread_notifications.length > 0">+{{membership_unread_notifications.length}}</span>
          </div>
      </a>

        <div class="dropdown-menu" aria-labelledby="dropdownEmergencyNotifications">
          <a class="dropdown-item" 
              v-for="membership_notification in membership_unread_notifications" 
              v-bind:key="membership_notification.id" 
              :href="'/request/membership/'+membership_notification.data.neighbor.id+'/'+membership_notification.id"
              >

            <div class="notification__icon-wrapper">
              <div class="notification__icon">
                <img :src="membership_notification.data.neighbor.avatar" alt="avatar" />
              </div>
            </div>
            <div class="notification__content">
              <span class="notification__category">{{membership_notification.data.title}}</span>
              <p>
                <!-- <span class="text-success text-semibold">JosueOb</span> -->
                {{membership_notification.data.description}}
              </p>
            </div>
          </a>

          <small class="dropdown-item text-muted" v-if="membership_unread_notifications.length === 0 && membership_notifications.length > 0">No tienes notificationes pendientes de afiliación</small>
          <a href="#" class="dropdown-item notification__all text-center" v-if="membership_notifications.length > 0">Ver notificaciones anteriores</a>
          <small class="dropdown-item text-muted" v-else>No tienes notificaciones de afiliación</small>
        </div>

</li>
</template>
<script>
export default {
//   props:['user'],
  data() {
    return {
      membership_unread_notifications: [],
      membership_notifications:[],
    };
  },
  //Se ejecuta una vez que se carga la página
  mounted(){
    axios.get('/api/notifications/memberships')
         .then(response => {
           this.membership_unread_notifications = response.data.unread_notifications;
           this.membership_notifications = response.data.membership_notifications;
         });
  }
};
</script>