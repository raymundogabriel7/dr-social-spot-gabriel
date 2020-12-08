<template>
    <div class="container">

        <div class="row justify-content-md-center">
            <div class="col-8">
                <h3>Newsfeed</h3>
                <div>
                    <div class="row">
                        <div class="col-9">
                            <form ref="postForm" autocomplete="off" @submit.prevent="createPost" method="post">
                                <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.password }">
                                    <input type="text" id="description" class="form-control" placeholder="Say something you like..." v-model.trim="$v.description.$model">
                                </div>
                            </form>
                        </div>
                        <div class="col-3">
                            <button type="button" class="btn btn-primary form-control" v-on:click="createPost">Post</button>
                        </div>
                    </div>
                    <br>
                    <div v-if="$v.description.$dirty">
                        <span class="help-block" v-if="!$v.description.required">Field is required</span>
                    </div>
                    <span class="help-block" v-if="has_error && errors.description">{{ errors.description }}</span>
                </div>
                <div class="card card-default" v-for="(post, index) in posts">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-7">
                                <h5>{{ post.user.name }}</h5>
                            </div>
                            <div class="col-5 text-right">
                                <em>{{ post.created_at | format_date }}</em>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                {{ post.description  }}
                            </div>
                        </div>
                        <hr v-if="post.comments.length > 0">
                        <div class="row">
                            <div class="col-11 offset-md-1">
                                <div v-for="(comment, index) in post.comments" :key="index">
                                    <div class="row">
                                        <div class="col-10">
                                            <b>{{comment.commented_user.name}}</b><br>
                                            <small>{{comment.comment}}</small>
                                            <div v-for="(reply, index) in comment.replies" :key="index" class="col-11 offset-md-1">
                                                <b>{{reply.replied_user.name}}</b><br>
                                                <small>{{reply.reply}}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-11 text-left">
                                            <input type="text" :class="[`btn-sm form-control comment--${index}`]" placeholder="Write your reply here..." v-on:keyup="createReply($event, comment.id)">
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12 text-left">
                                {{ post.likes.length }} likes {{ countComments(post.comments) }} comments
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-2 text-center">
                                <button type="button" v-if="post.like === null" class="btn btn-link btn-like" href="#" :id="[`like--${index}`]" v-on:click="createLike(post.id)">Like</button>
                                <button type="button" v-if="post.like !== null" class="btn btn-primary btn-like" href="#" :id="[`unlike--${index}`]" v-on:click="unlike(post.like.id)">Unlike</button>
                            </div>
                            <div class="col-10">
                                <input type="text" id="comment" class="form-control" placeholder="Write your comment here..." v-on:keyup="createComment($event, post.id)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import TokenService from '../../services/TokenService';
    import Vue from 'vue'
    import Vuelidate from 'vuelidate'
    import moment from 'moment'
    Vue.use(Vuelidate)
    import { required } from 'vuelidate/lib/validators'
    import axios from "axios";

    const TokenHelper = new TokenService();
    export default {
        data() {
            return {
                posts: [],
                id: 0,
                description: '',
                comment: '',
                has_error: false,
                error: '',
                errors: {},
                success: false
            }
        },
        mounted() {
          this.getPosts();
        },
        validations: {
            id: {},
            description: {
                required
            },
            comment: {}
        },
        components: {
            //
        },
        methods: {
            async getPosts() {
                this.posts = await axios.create({
                    headers: {Authorization: TokenHelper.buildBearerToken()}
                }).get(`post`).then(res => {
                    return res.data.data;
                }).catch(err => {
                    console.log(err)
                });
            },
            async createPost(event) {
                if (this.$v.$invalid) return;
                const app = this;
                const postData = {
                    description: app.description
                }
                await axios.create({
                    headers: {Authorization: TokenHelper.buildBearerToken()}
                }).post(`post`, postData).then(async res => {

                    app.has_error = false;
                    app.success = true;
                    app.descrption = '';
                    await this.getPosts();
                }).catch(err => {
                    app.has_error = true;
                    app.error = err.response.data.error
                    app.errors = err.response.data.errors || {}
                })
            },
            async createComment(e, postID) {
                if (e.keyCode === 13 && e.target.value != '') {
                    const app = this;
                    const commentData = {
                        comment: e.target.value,
                        post_id: postID
                    }
                    await axios.create({
                        headers: {Authorization: TokenHelper.buildBearerToken()}
                    }).post(`comment`, commentData).then(async res => {
                        app.has_error = false;
                        app.success = true;
                        await this.getPosts();
                        e.target.value = ''
                    }).catch(err => {
                        app.has_error = true;
                        app.error = err.response.data.error
                        app.errors = err.response.data.errors || {}
                    })
                }
            },
            async createReply(e, commentID) {
                if (e.keyCode === 13 && e.target.value != '') {
                    const app = this;
                    const replyData = {
                        reply: e.target.value,
                        comment_id: commentID
                    }
                    await axios.create({
                        headers: {Authorization: TokenHelper.buildBearerToken()}
                    }).post(`reply`, replyData).then(async res => {
                        app.has_error = false;
                        app.success = true;
                        await this.getPosts();
                        e.target.value = ''
                    }).catch(err => {
                        app.has_error = true;
                        app.error = err.response.data.error
                        app.errors = err.response.data.errors || {}
                    })
                }
            },
            async createLike(postID) {
                const app = this;
                const likeData = {
                    post_id: postID
                }
                await axios.create({
                    headers: {Authorization: TokenHelper.buildBearerToken()}
                }).post(`like`, likeData).then(async res => {
                    app.has_error = false;
                    app.success = true;
                    await this.getPosts();
                    e.target.value = ''
                }).catch(err => {
                    app.has_error = true;
                })
            },
            async unlike(likeID) {
                const app = this;
                await axios.create({
                    headers: {Authorization: TokenHelper.buildBearerToken()}
                }).delete(`like/${likeID}`).then(async res => {
                    app.has_error = false;
                    app.success = true;
                    await this.getPosts();
                    e.target.value = ''
                }).catch(err => {
                    app.has_error = true;
                })
            },
            countComments(comment) {
                let replies = 0;

                for (let i=0;i<comment.length;i++) {
                    replies = replies + comment[i].replies.length
                }

                return comment.length + replies
            }
        }
    }

    Vue.filter('format_date', function (value) {
        return moment(value).format('MMM DD, YYYY hh:mma')
    })
</script>
