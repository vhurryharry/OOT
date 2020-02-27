import { Component, OnInit } from "@angular/core";
import { Router } from "@angular/router";

import { LoginService, IUserInfo } from "src/app/services/login.service";
import { AccountService } from "./account.service";

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

  showPassword = false;

  loading = false;
  success = [null, null, null, null, null, null];
  errorMessage = [null, null, null, null, null, null];

  selectedNav = 1;

  constructor(
    private loginService: LoginService,
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

    this.greeting += this.userInfo.firstName;
  }

  ngOnInit() {}

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

  onSaveChanges(navIndex = 0) {
    this.loading = true;
    this.errorMessage[navIndex] = null;
    this.accountService.saveUserData(this.userInfo).subscribe((result: any) => {
      this.loading = false;
      if (result.success === false) {
        this.errorMessage[navIndex] = result.error;
        this.success[navIndex] = false;
      } else {
        this.success[navIndex] = true;
        this.loginService.updateUser(this.userInfo);
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

    if (navIndex === 0) {
      this.loginService.logOut();
      this.router.navigateByUrl("/");
    }
  }
}
