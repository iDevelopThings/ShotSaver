<template>

    <div>

        <div class="clearfix" v-if="response">
            <div class="pull-left">
                <div class="btn-group" role="group" aria-label="..." style="margin-bottom: 20px;">
                    <button type="button"
                            :disabled="paginating || (response.current_page <= 1)"
                            class="btn btn-default"
                            @click="getUploads('back')">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    <button type="button"
                            :disabled="paginating || (response.current_page >= response.last_page)"
                            class="btn btn-default"
                            @click="getUploads('forward')">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
            </div>
            <div class="pull-left" style="line-height: 36px; padding-left: 20px;">
                Page <strong>{{response.current_page}}</strong> of <strong>{{response.last_page}}</strong> | <strong>{{response.total}}</strong>
                results
            </div>
            <!--<div class="pull-right">

                <div class="btn-group" role="group" aria-label="..." style="margin-bottom: 20px;">
                    <div class="btn-group">
                        <button type="button"
                                class="btn btn-default dropdown-toggle"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            Filter: <strong>Created</strong> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#">Created</a></li>
                            <li><a href="#">Size</a></li>
                            <li><a href="#">Type</a></li>
                            <li><a href="#">Views</a></li>
                            <li><a href="#">Favourites</a></li>
                        </ul>
                    </div>
                    <div class="btn-group">
                        <button type="button"
                                class="btn btn-default dropdown-toggle"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            Order: <strong>Descending</strong> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#">Descending</a></li>
                            <li><a href="#">Ascending</a></li>
                        </ul>
                    </div>
                </div>

            </div>-->
        </div>

        <ul class="list-unstyled file-list" v-if="response">
            <li v-for="upload in response.data" :class="upload.loadingState ? 'disabled-state' : ''">

                <div class="row">
                    <div class="col-md-1 clearfix text-center">

                        <a href="javascript:;"
                           class="pull-left favourite-button"
                           @click="favourite(upload)"
                           :disabled="upload.loadingState">
                            <i class="fa" :class="upload.favourited_count === 1 ? 'fa-star' : 'fa-star-o'"></i>
                        </a>

                        <img :src="upload.thumbnail_url"
                             v-if="!upload.loadingState"
                             class="img-responsive center-block "
                             alt=""
                             style="height: 36px;">
                        <div v-else class="text-center"><i class="fa fa-spinner fa-spin"></i></div>
                    </div>
                    <div class="col-md-2">
                        <a :href="`/file/${upload.name}`">
                            <strong>{{upload.name}}</strong>
                        </a>
                    </div>
                    <div class="col-md-2">
                        Uploaded: <strong>{{upload.uploaded}}</strong>
                    </div>
                    <div class="col-md-1">
                        <div class="">Views: <strong>{{upload.views_count}}</strong></div>
                    </div>
                    <div class="col-md-1">
                        <div class="">Favourites: <strong>{{upload.total_favourites_count}}</strong></div>
                    </div>
                    <div class="col-md-1">
                        <div class="">{{upload.size}}MB</div>
                    </div>
                    <div class="col-md-1">
                        <div class="label label-primary">{{upload.type}}</div>
                    </div>
                    <div class="col-md-2">
                        <div class="btn-group btn-group-xs" role="group" aria-label="...">
                            <button type="button"
                                    class="btn btn-primary"
                                    @click="preview(upload)"
                                    :disabled="upload.loadingState">
                                <i class="fa fa-eye"></i> Preview
                            </button>
                            <!--<button type="button"
                                    class="btn btn-success"
                                    @click="favourite(upload)"
                                    :disabled="upload.loadingState">
                                <i class="fa fa-heart"></i> Favourite
                            </button>-->
                            <button type="button"
                                    class="btn btn-default"
                                    @click="remove(upload)"
                                    :disabled="upload.loadingState">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </div>

                    </div>
                </div>

            </li>
        </ul>

        <div class="modal fade" id="preview-modal" tabindex="-1" role="dialog" aria-labelledby="preview-modalLabel">
            <div class="modal-dialog" role="document" v-if="uploadToPreview">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color: white;">&times;</span></button>
                        <h4 class="modal-title" id="preview-modalLabel">Preview
                            <strong>{{uploadToPreview.name}}</strong></h4>
                    </div>
                    <div class="modal-body" style="padding: 0;">
                        <div v-if="uploadToPreview.type === 'Image'">
                            <img :src="uploadToPreview.link"
                                 alt=""
                                 class="img-responsive"
                                 style="height: 100%; width: 100%;">
                        </div>
                        <div v-if="uploadToPreview.type === 'Video'">
                            <div v-if="uploadToPreview.platform === 'streamable'">
                                <div v-html="uploadToPreview.embed"></div>
                            </div>
                            <div v-else>
                                <video class="img-responsive" controls :src="uploadToPreview.link"></video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
    export default {
        name    : "MyUploads",
        mounted()
        {
            this.getUploads();
        },
        data()
        {
            return {
                response   : null,
                paginating : false,
                loading    : false,
                error      : null,
                page       : 1,

                uploadToPreview : null,

                filters : {
                    filter : 'created',
                    order  : 'desc',
                }
            };
        },
        methods : {

            /**
             * Get a paginated list of the users uploads
             */
            getUploads(type = null)
            {
                let page = this.page;
                if (type === 'forward') {
                    if (this.response && this.response.last_page === page) return;
                    page++;
                    this.paginating = true;

                } else if (type === 'back') {
                    if (page === 1) return;

                    page--;
                    this.paginating = true;
                }

                console.log(page);

                this.loading = true;
                this.$http.get(`/api/files?page=${page}`)
                    .then(response => {
                        this.response   = response.data;
                        this.page       = parseInt(response.data.current_page);
                        this.loading    = false;
                        this.paginating = false;
                    })
                    .catch(error => {
                        this.error      = 'Failed to load uploads. Please try again later or contact support.';
                        this.loading    = false;
                        this.paginating = false;
                    })
            },

            preview(upload)
            {
                this.uploadToPreview = upload;
                $('#preview-modal').modal('show');
            },

            favourite(upload)
            {
                upload.loadingState = true;
                this.$http.post(`/api/files/${upload.id}/favourite`)
                    .then(response => {

                        upload.favourited_count       = response.data.favourited ? 1 : 0;
                        upload.total_favourites_count = response.data.total_favourites_count;

                        upload.loadingState = false;
                    })
                    .catch(error => {
                        upload.loadingState = false;
                    })

            },

            remove(upload)
            {
                if (!confirm('Are you sure you want to delete this file?')) return;

                let index = this.response.data.indexOf(upload);
                this.response.data.splice(index, 1);

                this.$http.delete(`/api/files/${upload.id}`)
                    .then(response => {

                    })
                    .catch(error => {
                        this.response.data.splice(index, 1, upload);
                    })
            }

        }
    }
</script>
