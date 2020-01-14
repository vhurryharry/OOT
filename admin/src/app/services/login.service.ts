import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

import { environment } from '../../environments/environment';
import { Observable, pipe, throwError } from 'rxjs';
import { map } from 'rxjs/operators';

export interface IUserInfo {
  id: number;
  name: string;
  email: string;
  firstName: string;
  lastName: string;
  permissions: string[];
  status: string;
  createdAt: Date;
  updatedAt: Date;
}

@Injectable()
export class LoginService {
  private authURL: string = environment.baseURL + `/api/auth/login`;
  currentUser: IUserInfo;
  authError: string;
  redirectUrl: string;

  constructor(private http: HttpClient) {}

  isLoggedIn(): boolean {
    if (localStorage.getItem('oot_admin_token')) {
      if (!this.currentUser) {
        this.currentUser = JSON.parse(localStorage.getItem('oot_admin_token'));
      }

      return true;
    }
    return false;
  }

  getCurrentUser(): IUserInfo {
    return this.currentUser;
  }

  getCurrentUserId(): number {
    return this.currentUser.id;
  }

  getPermissions(): string[] {
    return this.currentUser.permissions;
  }

  authenticate(email, password): Observable<IUserInfo> {
    return this.http
      .post<any>(`${this.authURL}`, {
        email,
        password
      })
      .pipe<any>(
        map(response => {
          if (response && response.success) {
            localStorage.setItem(
              'oot_admin_token',
              JSON.stringify(response.user)
            );

            this.currentUser = response.user;
            this.authError = null;

            return this.currentUser;
          } else {
            this.authError = response.error;
            this.currentUser = null;

            return throwError(response.error);
          }
        })
      );
  }

  logOut(): void {
    localStorage.removeItem('oot_admin_token');
    this.currentUser = null;
  }
}
