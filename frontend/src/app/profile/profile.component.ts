import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LoginService, IUserInfo } from '../services/login.service';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.scss']
})
export class ProfileComponent implements OnInit {
  user = {} as IUserInfo;
  constructor(private router: Router, private loginService: LoginService) {}

  ngOnInit() {
    this.user = this.loginService.getCurrentUser();
  }

  cmdLogout(): void {
    this.loginService.logOut();
    this.router.navigate(['/']);
  }
}
