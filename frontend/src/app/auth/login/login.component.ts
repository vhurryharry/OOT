import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LoginService } from '../../services/login.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
  email: string;
  password: string;
  errorMessage: string;
  result: any;

  constructor(private router: Router, private loginService: LoginService) {}

  ngOnInit() {}

  cmdLogin(): void {
    this.errorMessage = null;

    if (
      !this.email ||
      !this.email.length ||
      (!this.password || !this.password.length)
    ) {
      this.errorMessage = 'Invalid Email or Password';
      return;
    }

    this.loginService.authenticate(this.email, this.password).subscribe(
      user => {
        if (user && !this.loginService.authError) {
          if (this.loginService.redirectUrl) {
            this.router.navigateByUrl(this.loginService.redirectUrl);
          } else {
            this.router.navigate(['/']);
          }
        } else {
          this.errorMessage = this.loginService.authError;
        }
      },
      error => {
        this.errorMessage = 'Unexpected error occured!';
      }
    );
  }
}
