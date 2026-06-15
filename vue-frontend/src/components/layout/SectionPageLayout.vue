<template>
  <div class="section-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
      <span class="bc-link" @click="$router.push('/')">Home</span>
      <v-icon size="13" color="#90a4ae">mdi-chevron-right</v-icon>
      <span class="bc-current">{{ title }}</span>
    </div>

    <!-- Page header -->
    <div class="page-header">
      <div>
        <h1 class="page-title">{{ title }}</h1>
        <div class="title-bar"></div>
      </div>
      <slot name="header-actions" />
    </div>

    <!-- Page content -->
    <slot />
  </div>
</template>

<script>
export default { name: 'SectionPageLayout' }
</script>

<style scoped>
.section-page {
  padding: 28px 44px 48px;
  background: #f4f7fb;
  min-height: 100vh;
}
.breadcrumb {
  display: flex;
  align-items: center;
  gap: 6px;
  margin-bottom: 20px;
}
.bc-link {
  font-size: 13px;
  color: #1565C0;
  cursor: pointer;
  font-weight: 600;
}
.bc-link:hover { text-decoration: underline; }
.bc-current { font-size: 13px; color: #90a4ae; }

.page-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  margin-bottom: 28px;
}
.page-title {
  font-size: 24px;
  font-weight: 900;
  color: #0d1b2a;
  margin: 0 0 6px;
}
.title-bar {
  width: 40px;
  height: 3px;
  background: linear-gradient(90deg, #1565C0, #42a5f5);
  border-radius: 2px;
}
</style>