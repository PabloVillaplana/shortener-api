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
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"
                                   v-model="is_nsfw">
                            <label class="form-check-label" for="flexCheckChecked">
                                NSFW
                            </label>
                        </div>
                    </div>
                    <div v-if="is_loading">
                        <infinite-loading></infinite-loading>
                    </div>
                    <div class="form-group" v-if="short_url">

                        <main v-if="short_url.is_nsfw">
                            <a @click="redirectToShortUrl( short_url.short_url )"
                               style="cursor: pointer; text-decoration: underline; color: #ffc107">{{
                                short_url.short_url
                                }}</a>
                        </main>
                        <main v-else>
                            <strong>Short Url:</strong> <a :href="'/' + short_url.short_url"
                                                           target="_blank">{{ short_url.short_url }}</a>
                        </main>
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
                                <main v-if="top_100.is_nsfw">
                                    <a @click="redirectToShortUrl(top_100.short_url)"
                                       style="cursor: pointer; text-decoration: underline; color: #ffc107">{{
                                        base_url + '/' + top_100.short_url
                                        }}</a>
                                </main>
                                <main v-else>
                                    <a :href="'/' + top_100.short_url"
                                       target="_blank">{{ base_url + '/' + top_100.short_url }}</a>
                                </main>
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
            <div v-if="showModal">
                <transition name="modal">
                    <div class="modal-mask">
                        <div class="modal-wrapper">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">The link clicked is not NSFW it will be redirected to it
                                            in 10 seconds </h5>
                                    </div>
                                    <div class="modal-body">
                                        <infinite-loading></infinite-loading>
                                        <div style="text-align: center;">
                                            <button type="button" class="btn btn-warning"
                                                    @click="redirectToShortUrl('', true)">Stop and Back
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
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
            is_nsfw: 0,
            showModal: false,
            timer: null,
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

            let validation = this.validateUrl(this.url)


            if (validation == true) {
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
            } else {
                this.url_invalid = true;
            }
        },

        redirectToShortUrl: function (url, stop) {
            this.showModal = true;

            if (url != '') {
                this.timer = setTimeout(() => {
                    this.showModal = false;
                    window.open(this.base_url + '/' + url, '_blank')
                }, 10000);
            }

            if (stop) {
                clearTimeout(this.timer)
                this.showModal = false
            }
        },

        validateUrl: function (url) {
            var pattern = new RegExp('(https?:\\/\\/)?((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|((\\d{1,3}\\.){3}\\d{1,3}))(\\:\\d+)?(\\/[-a-z\\d%_.~+@]*)*(\\?[;&a-z\\d%_.~+=-@]*)?(\\#[-a-z\\d_@]*)?$', 'i')

            return pattern.test(url);
        }
    }
}
</script>

<style scoped>
.container {
    margin-top: 2em;
}

.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, .5);
    display: table;
    transition: opacity .3s ease;
}

.modal-wrapper {
    display: table-cell;
    vertical-align: middle;
}

</style>
