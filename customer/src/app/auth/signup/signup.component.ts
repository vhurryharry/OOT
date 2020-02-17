import { Component, OnInit } from "@angular/core";
import { LoginService } from "src/app/services/login.service";
import { Router } from "@angular/router";

@Component({
  selector: "app-signup",
  templateUrl: "./signup.component.html",
  styleUrls: ["./signup.component.scss", "../auth.component.scss"]
})
export class SignupComponent implements OnInit {
  fullName: string;
  email: string;
  password: string;
  business: string;
  errorMessage: string;

  constructor(private loginService: LoginService, private router: Router) {}

  ngOnInit() {}

  signup() {
    this.errorMessage = null;

    if (!this.fullName || !this.fullName.length) {
      this.errorMessage = "Invalid Full name";
      return;
    }

    if (
      !this.email ||
      !this.email.length ||
      (!this.password || !this.password.length)
    ) {
      this.errorMessage = "Invalid Email or Password";
      return;
    }

    if (this.password.length < 6) {
      this.errorMessage = "Password must be at least 6 characters";
      return;
    }

    const firstName = this.fullName.split(" ")[0];
    const lastName =
      this.fullName.split(" ").length === 1
        ? ""
        : this.fullName.substr(this.fullName.indexOf(" ") + 1);

    const data = {
      user: {
        email: this.email,
        login: this.email,
        password: this.password,
        firstName,
        lastName,
        occupation: this.business,
        type: "student"
      }
    };

    this.loginService.register(data).subscribe(
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
