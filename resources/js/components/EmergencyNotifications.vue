<template>
<li class="nav-item dropdown notifications m-0">
      <a class="nav-link nav-link-icon text-center" href="#" role="button" id="dropdownEmergencyNotifications" data-toggle='dropdown' aria-haspopup="true" aria-expanded="false">
          <div class="nav-link-icon__wrapper">
              <i class="fas fa-exclamation-circle"></i>
              <span class="badge badge-pill badge-danger" v-if="emergency_unread_notifications.length > 0">+{{emergency_unread_notifications.length}}</span>
          </div>
      </a>

        <div class="dropdown-menu" aria-labelledby="dropdownEmergencyNotifications">
          <a class="dropdown-item" 
              v-for="emergency_notification in emergency_unread_notifications" 
              v-bind:key="emergency_notification.id" 
              :href="'/request/emergency/'+emergency_notification.data.post.id+'/'+emergency_notification.id"
              >

            <div class="notification__icon-wrapper">
              <div class="notification__icon">
                <img :src="emergency_notification.data.neighbor.avatar" alt="avatar" />
              </div>
            </div>
            <div class="notification__content">
              <span class="notification__category">{{emergency_notification.data.title}}</span>
              <p>
                <!-- <span class="text-success text-semibold">JosueOb</span> -->
                {{emergency_notification.data.description}}
              </p>
            </div>
          </a>

          <small class="dropdown-item text-muted" v-if="emergency_unread_notifications.length === 0 && emergency_notifications.length > 0">No tienes notificationes pendientes de emergencias</small>
          <a href="/notifications/emergencies" class="dropdown-item notification__all text-center" v-if="emergency_notifications.length > 0">Ver notificaciones anteriores</a>
          <small class="dropdown-item text-muted" v-else>No tienes notificaciones de emergencias</small>
        </div>

</li>
</template>
<script>
export default {
//   props:['user'],
  data() {
    return {
      emergency_unread_notifications: [],
      emergency_notifications:[],
    };
  },
  //Se ejecuta una vez que se carga la página
  mounted(){
    axios.get('/api/notifications/emergencies')
         .then(response => {
           this.emergency_unread_notifications = response.data.unread_notifications;
           this.emergency_notifications = response.data.emergency_notifications;
         });
  }
};
</script>