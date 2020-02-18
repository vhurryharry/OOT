import { Component, OnInit } from "@angular/core";
import { LoginService } from "src/app/services/login.service";
import { Router, ActivatedRoute } from "@angular/router";

@Component({
  selector: "app-reset-pwd",
  templateUrl: "./reset-pwd.component.html",
  styleUrls: ["./reset-pwd.component.scss", "../auth.component.scss"]
})
export class ResetPwdComponent implements OnInit {
  token: string;

  email: string;
  password: string;

  errorMessage: string;
  tokenValidated = false;
  loading = false;

  constructor(
    private loginService: LoginService,
    private router: Router,
    private route: ActivatedRoute
  ) {
    this.route.queryParams.subscribe(params => {
      this.token = params.token;

      if (!this.token || !this.token.length) {
        this.errorMessage = "You're accessing this page from invalid location.";
        return;
      }

      this.loading = true;
      this.tokenValidated = false;

      this.loginService.resetPasswordValidateToken(this.token).subscribe(
        email => {
          this.loading = false;
          this.tokenValidated = true;
          this.email = email;
        },
        error => {
          this.loading = false;
          this.tokenValidated = false;
          this.errorMessage = error;
        }
      );
    });
  }

  ngOnInit() {}

  reset() {
    this.errorMessage = null;

    if (!this.password || !this.password.length) {
      this.errorMessage = "Invalid Password";
      return;
    }

    this.loading = true;
    this.loginService.resetPassword(this.email, this.password).subscribe(
      response => {
        this.loading = false;
        this.router.navigateByUrl("/login");
      },
      error => {
        this.loading = false;
        this.errorMessage = error;
      }
    );
  }
}
