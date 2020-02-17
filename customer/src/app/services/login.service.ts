import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { environment } from "../../environments/environment";
import { Observable, pipe, throwError } from "rxjs";
import { map } from "rxjs/operators";

export interface IUserInfo {
  id: number;
  metadata: string;
  type: string;
  email: string;
  status: string;
  firstName: string;
  lastName: string;
  acceptsMarketing: boolean;
  tagline: string;
  occupation: string;
  birthDate: Date;
  mfa: string;
}

@Injectable()
export class LoginService {
  private authURL: string = environment.baseURL + `/api/auth`;
  currentUser: IUserInfo;
  authError: string;
  redirectUrl: string;

  constructor(private http: HttpClient) {}

  isLoggedIn(): boolean {
    if (localStorage.getItem("oot_user_token")) {
      if (!this.currentUser) {
        this.currentUser = JSON.parse(localStorage.getItem("oot_user_token"));
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

  authenticate(email, password): Observable<IUserInfo> {
    return this.http
      .post<any>(`${this.authURL}/customer-login`, {
        email,
        password
      })
      .pipe<any>(
        map(response => {
          if (response && response.success) {
            localStorage.setItem(
              "oot_user_token",
              JSON.stringify(response.user)
            );

            this.currentUser = response.user;
            this.authError = null;

            return this.currentUser;
          } else {
            this.authError = response.error;
            this.currentUser = null;

            throw new Error(response.error);
          }
        })
      );
  }

  logOut(): void {
    localStorage.removeItem("oot_user_token");
    this.currentUser = null;
  }

  register(userInfo: any): Observable<IUserInfo> {
    return this.http
      .post<any>(`${this.authURL}/customer-register`, userInfo)
      .pipe<any>(
        map(response => {
          if (response && response.success === true) {
            localStorage.setItem(
              "oot_user_token",
              JSON.stringify(response.user)
            );

            this.currentUser = response.user;
            this.authError = null;

            return this.currentUser;
          } else {
            this.authError = response.error;
            this.currentUser = null;

            throw new Error(response.error);
          }
        })
      );
  }
}
