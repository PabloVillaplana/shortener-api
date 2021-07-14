<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="post" enctype="multipart/form-data" @submit="submitNewShortUrl">
                    <h2>Digite Url</h2>
                    <div class="form-group" v-if="is_loading == false">
                        <input type="url" id="homepage" name="homepage" required v-model="url">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" v-model="is_nsfw">
                            <label class="form-check-label" for="flexCheckChecked">
                                NSFW
                            </label>
                        </div>
                    </div>
                    <div v-if="is_loading">
                        <infinite-loading></infinite-loading>
                    </div>
                    <div class="form-group" v-if="short_url">
                        <strong>Short Url:</strong> <a :href="'/' + short_url.short_url" target="_blank">{{ short_url.short_url }}</a>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <h2>Top 100 most Frequently URLs</h2>
                <div>
                    <ol class="list-group list-group-numbered">
                        <li v-for="top_100 in top_100_url" v-if="top_100 != ''"
                            class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">{{ top_100.short_url }}</div>
                                <a :href="'/' + top_100.short_url" target="_blank">{{ base_url + '/' + top_100.short_url }}</a>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ top_100.visits }}</span>
                        </li>
                        <li v-if="errors" class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">{{ errors.error }}</div>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ...
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import InfiniteLoading from 'vue-infinite-loading';

export default {
    components: {
        InfiniteLoading,
    },

    data() {
        return {
            is_loading: false,
            top_100_url: [],
            errors: [],
            url: '',
            short_url: '',
            base_url: '',
            is_nsfw: 0
        }
    },
    created() {
        this.getTopUrls();
    },
    methods: {
        getTopUrls: function () {
            axios.get('/api/top-100').then((response => {
                this.base_url = window.location.origin;
                this.top_100_url = response.data;
                this.errors = []
            })).catch(e => {
                this.errors = e.response.data;
            })
        },

        submitNewShortUrl: function (e) {
            e.preventDefault();

            this.is_loading = true;
            this.short_url = [];

            axios.post('/api/generate/short_url', {
                url: this.url,
                is_nsfw: this.is_nsfw
            }).then((response => {
                this.getTopUrls();
                this.is_nsfw = 0;
                this.short_url = response.data;
                this.url = '';
                this.is_loading = false;
            }))

        },

        redirectToShortUrl: function (e) {
            console.log('eeee')
        }
    }
}
</script>

<style scoped>
.container {
    margin-top: 2em;
}
</style>
