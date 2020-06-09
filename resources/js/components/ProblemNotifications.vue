<template>
<li class="nav-item dropdown notifications m-0">
      <a class="nav-link nav-link-icon text-center" href="#" role="button" id="dropdownNotifications" data-toggle='dropdown' aria-haspopup="true" aria-expanded="false">
          <div class="nav-link-icon__wrapper">
              <i class="fas fa-times-circle"></i>
              <span class="badge badge-pill badge-danger" v-if="problems_unread_notifications.length > 0">+{{problems_unread_notifications.length}}</span>
          </div>
      </a>

        <div class="dropdown-menu" aria-labelledby="dropdownNotifications">
          <!-- <a class="dropdown-item" v-for="problem in problems" v-bind:key="problem"> -->
          <a class="dropdown-item" 
              v-for="problem_notification in problems_unread_notifications" 
              v-bind:key="problem_notification.id" 
              :href="'/request/socialProblem/'+problem_notification.data.post.id+'/'+problem_notification.id"
              >

            <div class="notification__icon-wrapper">
              <div class="notification__icon">
                <img :src="problem_notification.data.neighbor.avatar" alt="avatar" />
              </div>
            </div>
            <div class="notification__content">
              <span class="notification__category">{{problem_notification.data.title}}</span>
              <p>
                <!-- <span class="text-success text-semibold">JosueOb</span> -->
                {{problem_notification.data.description}}
              </p>
            </div>
          </a>

          <small class="dropdown-item text-muted" v-if="problems_unread_notifications.length === 0 && problems_notifications.length > 0">No tienes notificationes pendientes</small>
          <a href="/notifications/problems" class="dropdown-item notification__all text-center" v-if="problems_notifications.length > 0">Ver notificaciones anteriores</a>
          <small class="dropdown-item text-muted" v-else>No hay notificaciones</small>
        </div>

</li>
</template>
<script>
export default {
  props:['user'],
  data() {
    return {
      problems_unread_notifications: [],
      problems_notifications:[],
    };
  },
  //Se ejecuta una vez que se carga la página
  mounted(){
    axios.get('/api/notifications/problems')
         .then(response => {
           this.problems_unread_notifications = response.data.unread_notifications;
           this.problems_notifications = response.data.problem_notifications;
          console.log(response);
          console.log(this.problems_unread_notifications.length);
         });
  }
};
</script>