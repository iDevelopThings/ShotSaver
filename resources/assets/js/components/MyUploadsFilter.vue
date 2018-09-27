<template>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <label for="filter_by">Filter By:</label>
                <select name="filter_by" id="filter_by" class="form-control" v-model="filter_by">
                    <option value="created_at">Created</option>
                    <option value="size_in_bytes">Size</option>
                    <option value="ime_type">Type</option>
                </select>
            </div>
            <div class="form-group">
                <label for="order">Order</label>
                <select name="order" id="order" class="form-control" v-model="order">
                    <option value="desc">Descending</option>
                    <option value="asc">Ascending</option>
                </select>
            </div>
        </div>
        <div class="panel-footer">
            <button class="btn btn-block btn-default" @click="refresh" :disabled="loading">
                <i class="fa fa-refresh" :class="loading ? 'fa-spin' : ''"></i> Refresh Results
            </button>
        </div>
    </div>

</template>

<script>
    export default {
        name    : "MyUploadsFilter",
        props   : ['page', 'url', 'currentFilter', 'currentOrder'],
        mounted()
        {
            this.filter_by = this.currentFilter;
            this.order     = this.currentOrder;
        },
        data()
        {
            return {
                filter_by : 'created_at',
                order     : 'desc',
                loading   : false,
            };
        },
        methods : {
            refresh()
            {
                if (this.loading) return;

                this.loading = true

                window.location = `${this.url}?page=${this.page}&filter_by=${this.filter_by}&order=${this.order}`;
            }
        }
    }
</script>
