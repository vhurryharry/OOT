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

  onSaveChanges() {
    this.accountService.saveUserData(this.userInfo).subscribe(result => {});
  }
}
