import { Component, OnInit } from "@angular/core";
import { LoginService } from "src/app/services/login.service";
import { Router } from "@angular/router";

@Component({
  selector: "app-login",
  templateUrl: "./login.component.html",
  styleUrls: ["./login.component.scss", "../auth.component.scss"]
})
export class LoginComponent implements OnInit {
  email: string;
  password: string;

  errorMessage: string;

  constructor(private loginService: LoginService, private router: Router) {}

  ngOnInit() {}

  login() {
    this.errorMessage = null;

    if (
      !this.email ||
      !this.email.length ||
      (!this.password || !this.password.length)
    ) {
      this.errorMessage = "Invalid Email or Password";
      return;
    }

    this.loginService.authenticate(this.email, this.password).subscribe(
      user => {
        if (user) {
          if (this.loginService.redirectUrl) {
            this.router.navigateByUrl(this.loginService.redirectUrl);
          } else {
            this.router.navigate(["/"]);
          }
        }
      },
      error => {
        this.errorMessage = error;
      }
    );
  }
}
