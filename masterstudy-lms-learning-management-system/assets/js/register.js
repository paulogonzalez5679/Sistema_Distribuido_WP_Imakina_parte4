"use strict";

(function ($) {
  $(window).on('load', function () {
    stm_lms_register(true);
  });
})(jQuery);

function stm_lms_register(redirect) {
  var vue_obj = {
    el: '#stm-lms-register',
    data: function data() {
      return {
        vue_loaded: true,
        loading: false,
        login: '',
        email: '',
        password: '',
        password_re: '',
        message: '',
        status: '',
        site_key: '',
        become_instructor: '',
        degree: '',
        expertize: '',
        recaptcha: '',
        captcha_error: '',
        privacy_policy: true,
        has_privacy_policy: false,
        choose_auditory: false,
        auditory: '',
        additionalRegisterFields: [],
        additionalInstructorsFields: []
      };
    },
    mounted: function mounted() {
      if (typeof window.additionalRegisterFields !== 'undefined') {
        this.additionalRegisterFields = window.additionalRegisterFields;
      }

      if (typeof window.additionalInstructorsFields !== 'undefined') {
        this.additionalInstructorsFields = window.additionalInstructorsFields;
      }
    },
    methods: {
      hasPrivacyPolicy: function hasPrivacyPolicy() {
        if (!this.has_privacy_policy) {
          this.has_privacy_policy = true;
          this.privacy_policy = false;
        }
      },
      register: function register() {
        var vm = this;
        vm.loading = true;
        vm.message = '';
        var data = {
          'user_login': vm.login,
          'user_email': vm.email,
          'user_password': vm.password,
          'user_password_re': vm.password_re,
          'become_instructor': vm.become_instructor,
          'privacy_policy': vm.privacy_policy,
          'degree': vm.degree,
          'expertize': vm.expertize,
          'auditory': vm.auditory,
          'additional': vm.additionalRegisterFields,
          'additional_instructors': vm.additionalInstructorsFields
        };

        if (vm.site_key) {
          grecaptcha.ready(function () {
            grecaptcha.execute(vm.site_key, {
              action: 'register'
            }).then(function (token) {
              data['recaptcha'] = token;
              vm.processRegister(data);
            });
          });
        } else {
          vm.processRegister(data);
        }
      },
      processRegister: function processRegister(data) {
        var vm = this;
        vm.$http.post(stm_lms_ajaxurl + '?action=stm_lms_register&nonce=' + stm_lms_nonces['stm_lms_register'], data).then(function (response) {
          vm.message = response.body['message'];
          vm.status = response.body['status'];
          vm.loading = false;

          if (response.body['user_page']) {
            if (redirect) {
              window.location = response.body['user_page'];
            } else {
              location.reload();
            }
          }
        });
      },
      loadImage: function loadImage(index) {
        var form = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'register';
        var vm = this;

        if (vm.$refs['file-' + index][0].files[0]) {
          var fileToUpload = vm.$refs['file-' + index][0].files[0];
          var extensions;

          if (form === 'register') {
            extensions = typeof vm.additionalRegisterFields[index].extensions !== 'undefined' ? vm.additionalRegisterFields[index].extensions : '';
          } else {
            extensions = typeof vm.additionalInstructorsFields[index].extensions !== 'undefined' ? vm.additionalInstructorsFields[index].extensions : '';
          }

          vm.loading = true;

          if (fileToUpload) {
            var formData = new FormData();
            formData.append('file', fileToUpload);
            formData.append('extensions', extensions);
            formData.append('action', 'stm_lms_upload_form_file');
            formData.append('nonce', stm_lms_nonces['stm_lms_upload_form_file']);
            vm.$http.post(stm_lms_ajaxurl, formData).then(function (res) {
              if (typeof res['body'].url !== 'undefined') {
                if (form === 'register') {
                  vm.$set(vm.additionalRegisterFields[index], 'value', res['body'].url);
                } else {
                  vm.$set(vm.additionalInstructorsFields[index], 'value', res['body'].url);
                }

                vm.loading = false;
              }
            });
          }
        }
      }
    }
  };
  if (typeof Vue !== 'undefined') new Vue(vue_obj);
}