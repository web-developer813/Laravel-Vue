<template>

    <div id="app">

        <div class="panel panel-bordered">

            <div class="panel-body">

                <input type="hidden" name="_method" v-bind:value="method" />

                <div v-bind:class="hasDanger ? 'form-group form-material floating has-danger' : 'form-group form-material floating'" data-plugin="formMaterial">
                    <textarea v-model="content" name="content" class="form-control" rows="1" v-bind:disabled="loading"></textarea>
                    <label class="floating-label">Share Your Volunteering Experience...</label>
                </div>

            </div>

            <div class="panel-footer">

                <dropzone id="myVueDropzone"
                        ref="createPost"
                        url="api/posts"
                        :useFontAwesome="true"
                        :uploadMultiple="true"
                        v-on:vdropzone-success="showSuccess"
                        v-bind:dropzone-options="dropzoneOptions"
                        v-bind:use-custom-dropzone-options="true"
                        v-bind:preview-template="template">
                    <!-- Optional parameters if any! -->
                    <input type="hidden" id="content" name="content" v-model="content">

                </dropzone>


                <button type="button" class="btn btn-icon btn-success" ><i class="icon md-camera" aria-hidden="true" ></i></button>

                <button type="submit" ref="profile" class="btn btn-icon btn-primary ladda-button" data-style="zoom-in" @click="submitData()" @keyup.enter="submitData()">

                    <span class="ladda-label"><i class="icon md-mail-send" aria-hidden="true"></i> Post</span>

                </button>

            </div>

        </div>

    </div>

</template>

<script>

    import Dropzone from 'vue2-dropzone'
    import Ladda from 'ladda';
    import helpers from '../../mixins/common-helpers.js';

    export default {

        name: 'create-post',

        props: ['action','method'],

        mixins: [helpers],

        components: { Dropzone },

        data () {
            return {

                content: '',
                image: '',
                loading: false,
                hasDanger: false,
                hasMessage: false,
                message: '',

                dropzoneOptions: {
                    paramName: "media",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    autoProcessQueue: false,
                }
            }
        },

        watch: {
            content: function(val, oldVal) {
                var ladda = Ladda.create(document.querySelector('.ladda-button'));

                if (this.content.length > 2) {
                    this.hasDanger = false;
                    ladda.stop();
                }
            }
        },

        methods: {
            'showSuccess': function (file) {
                var ladda = Ladda.create(document.querySelector('.ladda-button'));

                this.notify('Successfully Posted','success')
                this.content = "";
                ladda.stop();

            },
            'submitData': function () {

                var ladda = Ladda.create(document.querySelector('.ladda-button'));

                this.loading = true;

                if (this.content.length < 2) {
                    this.hasDanger = true;
                    this.loading = false;
                    this.notify('Please share a little more before posting...','Keep Going','warning');
                }

                ladda.start();

                this.$refs.createPost.processQueue()

            },
            'template': function() {
                return `
                    <div class="dz-preview dz-file-preview">
                        <div class="dz-image" style="width: 200px;height: 200px">
                            <img data-dz-thumbnail />
                        </div>
                        <div class="dz-details">
                            <div class="dz-size"><span data-dz-size></span></div>
                            <div class="dz-filename"><span data-dz-name></span></div>
                        </div>
                        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                        <div class="dz-error-message"><span data-dz-errormessage></span></div>
                        <div class="dz-success-mark"><i class="fa fa-check"></i></div>
                        <div class="dz-error-mark"><i class="fa fa-close"></i></div>
                    </div>
                `;
            }
        }
    }
</script>

<style lang="less" scoped>
    .vue-dropzone {
        border: none;
        font-family: 'Arial', sans-serif;
        letter-spacing: 0.2px;
        color: #777;
        transition: background-color .2s linear;
        &:hover {
            background-color: black;
        }
        i {
            color: #CCC;
        }
        .dz-preview {
            .dz-image {
                border-radius: 1;
                &:hover {
                    img {
                        transform: none;
                        -webkit-filter: none;
                    }
                }
            }
            .dz-details {
                bottom: 0;
                top: 0;
                color: red;
                background-color: black;
                transition: opacity .2s linear;
                text-align: left;
                .dz-filename span, .dz-size span {
                    background-color: transparent;
                }
                .dz-filename:not(:hover) span {
                    border: none;
                }
                .dz-filename:hover span {
                    background-color: transparent;
                    border: none;
                }
            }
            .dz-progress .dz-upload {
                background: #cccccc;
            }
            .dz-remove {
                position: absolute;
                z-index: 30;
                color: white;
                margin-left: 15px;
                padding: 10px;
                top: inherit;
                bottom: 15px;
                border: 2px white solid;
                text-decoration: none;
                text-transform: uppercase;
                font-size: 0.8rem;
                font-weight: 800;
                letter-spacing: 1.1px;
                opacity: 0;
            }
            &:hover {
                .dz-remove {
                    opacity: 1;
                }
            }
            .dz-success-mark, .dz-error-mark {
                margin-left: auto !important;
                margin-top: auto !important;
                width: 100% !important;
                top: 35% !important;
                left: 0;
                i {
                    color: white !important;
                    font-size: 5rem !important;
                }
            }
        }
    }
</style>
