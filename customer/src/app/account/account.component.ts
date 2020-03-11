import { Component, OnInit } from "@angular/core";
import { Router } from "@angular/router";

import { LoginService, IUserInfo } from "src/app/services/login.service";
import { AccountService } from "./account.service";
import {
    PaymentService,
    IPaymentMethod,
    PaymentAction
} from "../services/payment.service";

declare var $: any;

@Component({
    selector: "app-account",
    templateUrl: "./account.component.html",
    styleUrls: ["./account.component.scss"]
})
export class AccountComponent implements OnInit {
    greeting = "Good Morning";
    userInfo: IUserInfo;
    avatarSource: File = null;

    oldPassword = "";
    newPassword = "";
    myCourses = [];

    showPassword = false;

    loading = false;
    success = [null, null, null, null, null, null];
    errorMessage = [null, null, null, null, null, null];
    coursesLoaded = false;
    paymentLoaded = false;
    savingAvatar = false;

    selectedNav = -2;
    selectedNavString = "Edit Profile";

    paymentMethods: IPaymentMethod[];

    constructor(
        private loginService: LoginService,
        private paymentService: PaymentService,
        private router: Router,
        private accountService: AccountService
    ) {
        if (!this.loginService.isLoggedIn()) {
            this.router.navigateByUrl("/login");
        }

        this.userInfo = this.loginService.getCurrentUser();
        if (!this.userInfo.avatar || this.userInfo.avatar === "") {
            this.userInfo.avatar = "/assets/images/images/auth/avatar.png";
        }

        const myDate = new Date();
        const hrs = myDate.getHours();

        if (hrs < 12) {
            this.greeting = "Good Morning, ";
        } else if (hrs >= 12 && hrs <= 17) {
            this.greeting = "Good Afternoon, ";
        } else if (hrs >= 17 && hrs <= 24) {
            this.greeting = "Good Evening, ";
        }

        this.greeting += this.userInfo.firstName + "!";

        this.accountService
            .getMyCourses(this.userInfo.id)
            .subscribe((result: any) => {
                this.coursesLoaded = true;
                if (result.success === true) {
                    this.myCourses = result.courses;
                } else {
                    this.errorMessage[3] = result.error;
                }
            });

        this.paymentService
            .getPaymentMethod(this.userInfo.id)
            .subscribe((result: any) => {
                this.paymentLoaded = true;

                if (result.success) {
                    this.paymentMethods = result.methods.map(method => {
                        return {
                            ...method,
                            expYear: method.expYear % 100,
                            brand: this.paymentService.getPaymentIcon(method.brand)
                        };
                    });
                } else {
                    this.errorMessage[4] =
                        "Error occured while getting your payment methods!";
                }
            });
    }

    ngOnInit() {
        if (this.paymentService.getAction() === PaymentAction.AddCard) {
            this.paymentService.setAction(null);
            this.onClickNav(4);
        }
    }

    onUpdateAvatar() {
        $("#account-profile-avatar").trigger("click");
    }

    onUpdateAvatarSource($event: Event) {
        this.avatarSource = ($event.target as any).files[0] as File;

        const reader = new FileReader();
        reader.onload = (e: any) => {
            this.userInfo.avatar = e.target.result;
        };

        // This will process our file and get it"s attributes/data
        reader.readAsDataURL(this.avatarSource);
    }

    onRemoveAvatar() {
        this.avatarSource = null;
        this.userInfo.avatar = "/assets/images/images/auth/avatar.png";
    }

    onSaveChanges(navIndex = 0) {
        this.loading = true;
        this.errorMessage[navIndex] = null;

        this.accountService.saveUserData(this.userInfo).subscribe((result: any) => {
            if (result.success === false) {
                this.loading = false;
                this.errorMessage[navIndex] = result.error;
                this.success[navIndex] = false;
            } else {
                if (this.avatarSource !== null) {
                    this.loading = true;
                    this.accountService
                        .saveUserAvatar(this.avatarSource, this.userInfo)
                        .subscribe((avatarResult: any) => {
                            this.loading = false;
                            this.avatarSource = null;
                            if (avatarResult.success === false) {
                                this.errorMessage[navIndex] = avatarResult.error;
                                this.success[navIndex] = false;
                            } else {
                                this.success[navIndex] = true;
                                this.userInfo.avatar = avatarResult.avatar;
                                this.loginService.updateUser(this.userInfo);
                            }
                        });
                } else {
                    this.loading = false;
                    this.success[navIndex] = true;
                    this.loginService.updateUser(this.userInfo);
                }
            }
        });
    }

    onSavePasswordChanges() {
        this.loading = true;
        this.errorMessage[2] = null;
        this.accountService
            .changePassword(this.userInfo.login, this.oldPassword, this.newPassword)
            .subscribe((result: any) => {
                this.loading = false;
                if (result.success === false) {
                    this.errorMessage[2] = result.error;
                    this.success[2] = false;
                } else {
                    this.success[2] = true;

                    this.oldPassword = "";
                    this.newPassword = "";
                }
            });
    }

    toggleShowPassword() {
        this.showPassword = !this.showPassword;
    }

    onClickNav(navIndex) {
        this.selectedNav = navIndex;

        switch (navIndex) {
            case 1:
                this.selectedNavString = "Edit Profile";
                break;

            case 2:
                this.selectedNavString = "Password Change";
                break;

            case 3:
                this.selectedNavString = "My Courses";
                break;

            case 4:
                this.selectedNavString = "Payment information";
                break;

            case 5:
                this.selectedNavString = "Social Profiles";
                break;

            case 6:
                this.selectedNavString = "Billings";
                break;
        }

        if (navIndex === -1) {
            this.loginService.logOut();
            this.router.navigateByUrl("/");
        }
    }

    onGetCertificate(id: string) {
        console.log(id);
    }

    onLeaveFeedback(id: string) {
        console.log(id);
    }

    removePayment(index) {
        this.loading = true;

        this.paymentService
            .removePaymentMethod(this.userInfo.id, this.paymentMethods[index].id)
            .subscribe((result: any) => {
                if (result.success) {
                    this.loading = false;
                    this.success[4] = true;
                    this.paymentMethods.splice(index, 1);
                } else {
                    this.success[4] = false;
                    this.errorMessage[4] = result.error;
                }
            });
    }

    addPaymentMethod() {
        this.paymentService.setAction(PaymentAction.AddCard);
        this.router.navigateByUrl("/payment");
    }

    showCourse(slug) {
        this.router.navigateByUrl("/course/" + slug);
    }
}
