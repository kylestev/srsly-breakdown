<template>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ name }} Server Breakdown</h3>
    </div>
    <table class="table table-striped">
      <tbody>
        <tr v-for="(countryName, servers) in countries">
          <td>{{ countryName }}</td>
          <td>{{ servers }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script lang="es6">
  module.exports = {
    props: ['name', 'countries']
  }
</script>
