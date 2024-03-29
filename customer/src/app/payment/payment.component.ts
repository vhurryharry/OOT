import { Component, OnInit } from "@angular/core";
import { Router } from "@angular/router";

import { LoginService, IUserInfo } from "../services/login.service";
import { PaymentAction, PaymentService } from "../services/payment.service";

import { countryList } from "../services/constants";

@Component({
  selector: "app-payment",
  templateUrl: "./payment.component.html",
  styleUrls: ["./payment.component.scss"]
})
export class PaymentComponent implements OnInit {
  addCard = true;
  userInfo: IUserInfo;
  currentStep = 1;
  countryList = [];

  error: string;
  loading = false;

  public startAt = new Date(1990, 1, 1);
  public maxDate = new Date().setFullYear(new Date().getFullYear() - 17);

  billingDetails = {
    firstName: "",
    lastName: "",
    company: "",
    country: "",
    city: "",
    street: "",
    state: "",
    zip: ""
  };

  attendeeInformation = {
    firstName: "",
    lastName: "",
    title: "",
    birthDate: null,
    occupation: "",
    phone: "",
    email: ""
  };

  clientSecret = "";

  showError = 0;

  constructor(
    private loginService: LoginService,
    private paymentService: PaymentService,
    private router: Router
  ) {
    if (
      !this.loginService.isLoggedIn() ||
      this.paymentService.getAction() === PaymentAction.RegisterAndPay
    ) {
      this.addCard = false;
    } else {
      this.addCard = true;
    }

    this.countryList = countryList;

    if (this.loginService.isLoggedIn()) {
      const userInfo = this.loginService.getCurrentUser();
      this.userInfo = userInfo;

      this.billingDetails = {
        ...this.billingDetails,
        firstName: userInfo.firstName,
        lastName: userInfo.lastName
      };

      this.attendeeInformation = {
        ...this.attendeeInformation,
        firstName: userInfo.firstName,
        lastName: userInfo.lastName,
        birthDate: userInfo.birthDate,
        occupation: userInfo.occupation,
        phone: userInfo.phone,
        title: userInfo.title,
        email: userInfo.login
      };
    }
  }

  ngOnInit() {}

  nextStep() {
    this.showError = 0;

    if (this.currentStep === 1) {
      if (
        this.billingDetails.firstName === "" ||
        this.billingDetails.lastName === "" ||
        this.billingDetails.country === "" ||
        this.billingDetails.city === "" ||
        this.billingDetails.street === "" ||
        this.billingDetails.zip === ""
      ) {
        this.showError = 1;
      } else {
        this.loading = true;

        this.paymentService
          .getClientSecret(
            this.userInfo.id,
            this.billingDetails,
            this.attendeeInformation
          )
          .subscribe((result: any) => {
            this.loading = false;
            if (result && result.success) {
              this.clientSecret = result.secret;
              this.currentStep++;
            } else {
              this.error = result.error;
            }
          });
      }
    }

    // if (this.currentStep === 2) {
    //     if (
    //         this.attendeeInformation.firstName === "" ||
    //         this.attendeeInformation.lastName === "" ||
    //         this.attendeeInformation.birthDate === null ||
    //         this.attendeeInformation.title === "" ||
    //         this.attendeeInformation.phone === "" ||
    //         this.attendeeInformation.email === ""
    //     ) {
    //         this.showError = 2;
    //     }
    // }
  }

  onTokenReady(pmToken) {
    if (this.addCard) {
      this.error = null;
      this.loading = true;

      this.paymentService
        .addPaymentMethod(this.userInfo.id, this.clientSecret, pmToken)
        .subscribe((result: any) => {
          this.loading = false;
          if (result.success) {
            const redirectUrl = this.paymentService.redirectUrl
              ? this.paymentService.redirectUrl
              : "/account";
            this.paymentService.redirectUrl = null;
            this.router.navigateByUrl(redirectUrl);
          } else {
            this.error = result.error;
          }
        });
    }
  }

  onErrorOccured(error) {
    this.error = error;
    this.loading = false;
  }

  onStartLoading() {
    this.loading = true;
  }
}
