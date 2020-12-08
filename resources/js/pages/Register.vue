<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-6">
        <div class="card card-default">
          <div class="card-header">Register</div>
          <div class="card-body">
            <div class="alert alert-danger" v-if="has_error && !success">
                <p v-if="error == 'registration_validation_error'">Validation Errors.</p>
                <p v-else>Error, can not register at the moment. If the problem persists, please contact an administrator.</p>
            </div>
              <div class="alert alert-success" v-if="!has_error && success">
                  <p>Successfully created!.</p>
              </div>
            <form autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
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
                <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.password }">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control" placeholder="Password" v-model.trim="$v.password.$model">
                    <div v-if="$v.password.$dirty">
                        <span class="help-block" v-if="!$v.password.required">Field is required</span>
                    </div>
                    <span class="help-block" v-if="has_error && errors.password">{{ errors.password }}</span>
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.password }">
                    <label for="password_confirmation">Password confirmation</label>
                    <input type="password" id="password_confirmation" class="form-control" placeholder="Password confirmation" v-model.trim="$v.password_confirmation.$model">
                    <div v-if="$v.password_confirmation.$dirty">
                        <span class="help-block" v-if="!$v.password_confirmation.required">Field is required</span>
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
  import axios from "axios";
  import TokenService from '../services/TokenService';
  import Vue from 'vue'
  import Vuelidate from 'vuelidate'
  Vue.use(Vuelidate)
  import { required, email, sameAs } from 'vuelidate/lib/validators'

  const TokenHelper = new TokenService();
  export default {
    data() {
      return {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        has_error: false,
        error: '',
        errors: {},
        success: false
      }
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
            required
        },
        password_confirmation: {
            required,
            sameAsPassword: sameAs('password')
        }
      },
    methods: {
      register() {
        if (this.$v.$invalid) return;
        var app = this
        this.$auth.register({
          data: {
            name: app.name,
            email: app.email,
            password: app.password,
            password_confirmation: app.password_confirmation
          },
          success: async function (res) {
            app.success = true
            app.has_error = false
              const user = res.data.user;
            console.log(user)
              const friendsData = {
                  friend_id: user.id
              }
              //Save the new user as friend
              await axios.create({
                  headers: {Authorization: TokenHelper.buildBearerToken()}
              }).post(`friends`, friendsData).then(async res => {
                  app.has_error = false;
                  app.success = true;
              }).catch(err => {
                  app.has_error = true;
                  app.error = err.response.data.error
                  app.errors = err.response.data.errors || {}
              })
            //this.$router.push({name: 'login', params: {successRegistrationRedirect: true}})
          },
          error: function (res) {
            app.has_error = true
            app.error = res.response.data.error
            app.errors = res.response.data.errors || {}
          }
        })
      }
    }
  }
</script>
