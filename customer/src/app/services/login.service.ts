import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { environment } from "../../environments/environment";
import { Observable, pipe, throwError } from "rxjs";
import { map, catchError } from "rxjs/operators";

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
      )
      .pipe(
        catchError(error => throwError(new Error("Unexpected error occured!")))
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
      )
      .pipe(
        catchError(error => throwError(new Error("Unexpected error occured!")))
      );
  }

  resetPasswordRequest(email: string): Observable<boolean> {
    return this.http
      .post<any>(`${this.authURL}/customer-reset-password-requested`, { email })
      .pipe<any>(
        map(response => {
          if (response && response.success === true) {
            return true;
          } else {
            throw new Error(response.error);
          }
        })
      )
      .pipe(
        catchError(error => throwError(new Error("Unexpected error occured!")))
      );
  }

  resetPassword(email: string, password: string): Observable<boolean> {
    return this.http
      .post<any>(`${this.authURL}/customer-reset-password`, { email, password })
      .pipe<any>(
        map(response => {
          if (response && response.success === true) {
            return true;
          } else {
            throw new Error(response.error);
          }
        })
      )
      .pipe(
        catchError(error => throwError(new Error("Unexpected error occured!")))
      );
  }

  resetPasswordValidateToken(token: string): Observable<string> {
    return this.http
      .post<any>(`${this.authURL}/customer-reset-password-validate-token`, {
        token
      })
      .pipe<any>(
        map(response => {
          if (response && response.success === true) {
            return response.email;
          } else {
            throw new Error(response.error);
          }
        })
      )
      .pipe(
        catchError(error => throwError(new Error("Unexpected error occured!")))
      );
  }

  handleError(error: any): void {
    throw new Error("Unexpected error occured!");
  }
}
