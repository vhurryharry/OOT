import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'admin-login',
  templateUrl: './login.component.html'
})
export class LoginComponent implements OnInit {
  username: string;
  password: string;
  errorMessage: string;
  result: any;

  ngOnInit() {}

  cmdLogin(): void {
    this.errorMessage = null;

    if (
      !this.username ||
      !this.username.length ||
      (!this.password || !this.password.length)
    ) {
      this.errorMessage = 'Invalid Username or Password';
      return;
    }
  }
}
