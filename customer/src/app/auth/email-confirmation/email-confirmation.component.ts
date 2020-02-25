import { Component, OnInit } from "@angular/core";
import { LoginService } from "src/app/services/login.service";
import { Router, ActivatedRoute } from "@angular/router";

@Component({
  selector: "app-email-confirmation",
  templateUrl: "./email-confirmation.component.html",
  styleUrls: ["./email-confirmation.component.scss", "../auth.component.scss"]
})
export class EmailConfirmationComponent implements OnInit {
  token: string;

  errorMessage: string;
  tokenValidated = false;
  loading = false;

  timeout = 3;
  interval: any;

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

      this.loginService.confirmationValidateToken(this.token).subscribe(
        user => {
          this.loading = false;
          this.tokenValidated = true;

          this.interval = setInterval(() => {
            this.timeout--;

            if (this.timeout <= 0) {
              clearInterval(this.interval);

              this.router.navigateByUrl("/");
            }
          }, 1000);
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
}
