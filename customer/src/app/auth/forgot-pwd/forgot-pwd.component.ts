import { Component, OnInit } from "@angular/core";
import { LoginService } from "src/app/services/login.service";
import { Router } from "@angular/router";

@Component({
  selector: "app-reset-pwd",
  templateUrl: "./forgot-pwd.component.html",
  styleUrls: ["./forgot-pwd.component.scss", "../auth.component.scss"]
})
export class ForgotPwdComponent implements OnInit {
  email: string;

  errorMessage: string;
  loading = false;
  sentLink = false;

  constructor(private loginService: LoginService, private router: Router) {}

  ngOnInit() {}

  request() {
    this.errorMessage = null;

    if (!this.email || !this.email.length) {
      this.errorMessage = "Invalid Email address";
      return;
    }

    this.loading = true;
    this.loginService.resetPasswordRequest(this.email).subscribe(
      response => {
        this.sentLink = true;
        this.loading = false;
      },
      error => {
        this.sentLink = false;
        this.loading = false;
        this.errorMessage = error;
      }
    );
  }
}
