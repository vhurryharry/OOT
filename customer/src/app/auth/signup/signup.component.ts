import { Component, OnInit } from "@angular/core";
import { LoginService } from "src/app/services/login.service";
import { Router } from "@angular/router";

@Component({
  selector: "app-signup",
  templateUrl: "./signup.component.html",
  styleUrls: ["./signup.component.scss", "../auth.component.scss"]
})
export class SignupComponent implements OnInit {
  firstName: string;
  lastName: string;
  email: string;
  password: string;
  business: string;
  phone: string;
  title: string;

  errorMessage: string;
  loading = false;
  confirmationSent = false;
  birthDate: Date;

  public startAt = new Date(1990, 1, 1);
  public maxDate = new Date().setFullYear(new Date().getFullYear() - 17);

  constructor(private loginService: LoginService, private router: Router) {}

  ngOnInit() {}

  signup() {
    this.errorMessage = null;

    if (
      !this.firstName ||
      !this.firstName.length ||
      !this.lastName ||
      !this.lastName.length
    ) {
      this.errorMessage = "Invalid Name";
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

    this.loading = true;

    console.log(this.birthDate);

    const data = {
      user: {
        email: this.email,
        login: this.email,
        password: this.password,
        firstName: this.firstName,
        lastName: this.lastName,
        phone: this.phone,
        title: this.title,
        birthDate: new Date(
          this.birthDate.getTime() - this.birthDate.getTimezoneOffset() * 60000
        ),
        occupation: this.business,
        type: "student"
      }
    };

    this.loginService.register(data).subscribe(
      user => {
        this.loading = false;
        this.confirmationSent = true;
      },
      error => {
        this.loading = false;
        this.errorMessage = error;
      }
    );
  }
}
