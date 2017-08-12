<template>
  <a @click.prevent="showAddBalanceModal" class="btn btn-default">
    <i class="glyphicon glyphicon-eur"></i> Top Up Employee Balance
  </a>
</template>

<script>
import bus from '../bus';

export default {
  methods: {
    showAddBalanceModal() {
      bus.$emit('add-balance-modal:show');
    }
  }
}
</script>
