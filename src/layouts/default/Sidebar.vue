<template>
  <aside class="admin-sidebar">
    <header class="mb-5">
      <ul class="py-5">
        <li class="admin-logo">
          <span>
            <img src="src/assets/brand.png" alt="SQ Logo" />
          </span>
          <span>Spritual Quotient</span>
        </li>
      </ul>
      <div class="toggler" @click="toggleSidebar()">
        <span class="toggle"></span>
      </div>
    </header>
    <ul class="menus">
      <li>
        <span><img src="src/assets/search.svg" alt="Search" /></span>
        <span>Search</span>
      </li>
      <li>
        <!-- <router-link :to="{ name: 'messages' }"> -->
        <span><img src="src/assets/messages.svg" alt="Messages" /></span>
        <span>Messages</span
        ><!-- </router-link
        > -->
      </li>
      <li class="active">
        <router-link :to="{ name: 'home-page' }">
          <span><img src="src/assets/pray.svg" alt="Pray" /></span>
          <span>Pray</span>
        </router-link>
      </li>
      <li>
        <span><img src="src/assets/meditate.svg" alt="Meditate" /></span>
        <span>Meditate</span>
      </li>
      <li>
        <span><img src="src/assets/proclaim.svg" alt="proclaim" /></span>
        <span>Proclaim</span>
      </li>
      <li>
        <router-link :to="{ name: 'fellowship' }">
          <span><img src="src/assets/fellowship.svg" alt="fellowship" /></span>
          <span>Fellowship </span>
        </router-link>
      </li>
      <li>
        <span><img src="src/assets/add prayer.svg" alt="prayer" /></span>
        <span>Add Prayer</span>
      </li>
      <li>
        <span><img src="src/assets/custom shortcut.svg" alt="custom" /></span>
        <span>Custom Shortcut</span>
      </li>
      <li>
        <span><img src="src/assets/help.svg" alt="help" /></span>
        <span>Help</span>
      </li>
    </ul>
  </aside>
</template>

<script>
export default {
  methods: {
    toggleSidebar() {
      let adminSidebar = document.querySelector(".admin-sidebar");
      adminSidebar.classList.toggle("active");
    },
  },
};
</script>
