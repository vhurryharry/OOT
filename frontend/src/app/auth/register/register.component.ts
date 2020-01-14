import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LoginService } from '../../services/login.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent implements OnInit {
  email: string;
  password: string;
  confirmPassword: string;
  firstName: string;
  lastName: string;
  dob: Date;
  tagline: string;
  occupation: string;
  acceptsMarketing: boolean;
  type: string;

  errorMessage: string;
  result: any;

  constructor(private router: Router, private loginService: LoginService) {}

  ngOnInit() {}

  cmdRegister(): void {
    this.errorMessage = null;

    if (
      !this.email ||
      !this.email.length ||
      (!this.password || !this.password.length)
    ) {
      this.errorMessage = 'Invalid Email or Password';
      return;
    }

    if (this.password !== this.confirmPassword) {
      this.errorMessage = 'Passwords do not match';
      return;
    }

    this.loginService
      .register({
        user: {
          email: this.email,
          login: this.email,
          password: this.password,
          firstName: this.firstName,
          lastName: this.lastName,
          tagline: this.tagline,
          occupation: this.occupation,
          birthDate: this.dob,
          type: this.type
        }
      })
      .subscribe(
        user => {
          if (user) {
            if (this.loginService.redirectUrl) {
              this.router.navigateByUrl(this.loginService.redirectUrl);
            } else {
              this.router.navigate(['/']);
            }
          }
        },
        error => {
          this.errorMessage = error;
        }
      );
  }
}
