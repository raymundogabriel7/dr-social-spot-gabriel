<template>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-6">
                <div class="alert alert-success" v-if="!has_error && success">
                    <p>Successfully updated!</p>
                </div>
                <div class="alert alert-danger" v-if="has_error && !success">
                    <p v-if="error === 'registration_validation_error'">Validation Errors.</p>
                    <p v-else>Error, can not register at the moment.</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-md-center">
            <div class="col-6">
                <div class="card card-default">
                    <div class="card-header">Edit Profile</div>
                    <div class="card-body">
                        <form ref="profileForm" autocomplete="off" @submit.prevent="updateProfile" method="post">
                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.name }">
                                <label for="name">Name</label>
                                <input type="text" id="name" class="form-control" placeholder="Full Name" v-model.trim="$v.name.$model">
                                <div v-if="$v.name.$dirty">
                                    <span class="help-block" v-if="!$v.name.required">Field is required</span>
                                </div>
                                <span class="help-block" v-if="has_error && errors.name">{{ errors.name }}</span>
                            </div>
                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.email }">
                                <label for="email">E-mail</label>
                                <input type="text" id="email" class="form-control" placeholder="Email" v-model.trim="$v.email.$model">
                                <div v-if="$v.email.$dirty">
                                    <span class="help-block" v-if="!$v.email.required">Field is required</span>
                                    <span class="help-block" v-if="!$v.email.email">Wrong email format</span>
                                </div>
                                <span class="help-block" v-if="has_error && errors.email">{{ errors.email }}</span>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row justify-content-md-center">
            <div class="col-6">
                <div class="card card-default">
                    <div class="card-header">Edit Password</div>
                    <div class="card-body">
                        <form ref="passwordForm" autocomplete="off" @submit.prevent="updatePassword" method="post">
                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.password }">
                                <label for="password">Password</label>
                                <input type="password" id="password" class="form-control" placeholder="Password" v-model.trim="$v.password.$model">
                                <span class="help-block" v-if="has_error && errors.password">{{ errors.password }}</span>
                            </div>
                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.password }">
                                <label for="password_confirmation">Password confirmation</label>
                                <input type="password" id="password_confirmation" class="form-control" placeholder="Password confirmation" v-model.trim="$v.password_confirmation.$model">
                                <div v-if="$v.password_confirmation.$dirty">
                                    <span class="help-block" v-if="!$v.password_confirmation.sameAsPassword">Passwords must be identical </span>
                                </div>
                                <span class="help-block" v-if="has_error && errors.password_confirmation">{{ errors.password_confirmation }}</span>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios';
    import TokenService from '../../services/TokenService';
    import Vue from 'vue'
    import Vuelidate from 'vuelidate'
    Vue.use(Vuelidate)
    import { required, email, sameAs } from 'vuelidate/lib/validators'
    const TokenHelper = new TokenService();

    export default {
        data() {
            return {
                id: 0,
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                has_error: false,
                error: '',
                errors: {},
                success: false      }
        },
        validations: {
            name: {
                required
            },
            email: {
                required,
                email
            },
            password: {
                min: 3
            },
            password_confirmation: {
                min: 3,
                sameAsPassword: sameAs('password')
            }
        },
        mounted() {
            this.getProfile();
        },
        methods: {
            async getProfile() {
                const app = this;
                await this.$auth.fetch({
                    success: function (res) {
                        const userData = res.data.data;

                        app.name = userData['name']
                        app.email = userData['email']
                        app.id = userData['id']
                    },
                    error: function (res) {
                        console.log(res.response.data.errors)
                    }
                })
            },
            async updateProfile(event) {
                if (this.$v.$invalid) {
                    return;
                }
                const app = this;
                const userData = {
                    name: app.name,
                    email: app.email,
                }
                await axios.create({
                    headers: {Authorization: TokenHelper.buildBearerToken()}
                }).put(`user/${app.id}`, userData).then(res => {
                    app.has_error = false;
                    app.success = true;
                    event.target.reset();
                }).catch(err => {
                    app.has_error = true
                    app.error = err.response.data.error
                    app.errors = err.response.data.errors || {}
                })
            },
            async updatePassword(event) {
                if (this.$v.$invalid) return;
                const app = this;
                const passwordData = {
                    password: app.password
                }
                await axios.create({
                    headers: {Authorization: TokenHelper.buildBearerToken()}
                }).put(`user-password/${app.id}`, passwordData).then(res => {
                    app.has_error = false;
                    app.success = true;
                    app.password = '';
                    app.password_confirmation = '';
                    event.target.reset();
                }).catch(err => {
                    app.has_error = true
                    app.error = err.response.data.error
                    app.errors = err.response.data.errors || {}
                })
            }
        }
    }
</script>
