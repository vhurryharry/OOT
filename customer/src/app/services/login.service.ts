import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { environment } from "../../environments/environment";
import { Observable, pipe, throwError } from "rxjs";
import { map, catchError } from "rxjs/operators";

export interface IUserInfo {
  id: string;
  type: string;
  login: string;
  phone: string;
  title: string;
  firstName: string;
  lastName: string;
  occupation: string;
  birthDate: Date;
  bio: string;
  website: string;
  instagram: string;
  twitter: string;
  facebook: string;
  avatar: string;
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
    if (this.isLoggedIn()) {
      return this.currentUser;
    }
    return null;
  }

  getCurrentUserId(): string {
    if (this.isLoggedIn()) {
      return this.currentUser.id;
    }
    return null;
  }

  authenticate(email, password): Observable<IUserInfo> {
    return this.http
      .post<any>(`${this.authURL}/customer-login`, {
        email,
        password
      })
      .pipe(
        catchError(error => throwError(new Error("Unexpected error occured!")))
      )
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
    this.redirectUrl = null;
  }

  updateUser(updatedUser): void {
    this.currentUser = {
      ...this.currentUser,
      ...updatedUser
    };

    localStorage.setItem("oot_user_token", JSON.stringify(this.currentUser));
  }

  register(userInfo: any): Observable<IUserInfo> {
    return this.http
      .post<any>(`${this.authURL}/customer-register`, userInfo)
      .pipe(
        catchError(error => throwError(new Error("Unexpected error occured!")))
      )
      .pipe<any>(
        map(response => {
          if (response && response.success === true) {
            this.currentUser = null;
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

  resetPasswordRequest(email: string): Observable<boolean> {
    return this.http
      .post<any>(`${this.authURL}/customer-reset-password-requested`, { email })
      .pipe(
        catchError(error => throwError(new Error("Unexpected error occured!")))
      )
      .pipe<any>(
        map(response => {
          if (response && response.success === true) {
            return true;
          } else {
            throw new Error(response.error);
          }
        })
      );
  }

  resetPassword(email: string, password: string): Observable<boolean> {
    return this.http
      .post<any>(`${this.authURL}/customer-reset-password`, { email, password })
      .pipe(
        catchError(error => throwError(new Error("Unexpected error occured!")))
      )
      .pipe<any>(
        map(response => {
          if (response && response.success === true) {
            return true;
          } else {
            throw new Error(response.error);
          }
        })
      );
  }

  resetPasswordValidateToken(token: string): Observable<string> {
    return this.http
      .post<any>(`${this.authURL}/customer-reset-password-validate-token`, {
        token
      })
      .pipe(
        catchError(error => throwError(new Error("Unexpected error occured!")))
      )
      .pipe<any>(
        map(response => {
          if (response && response.success === true) {
            return response.email;
          } else {
            throw new Error(response.error);
          }
        })
      );
  }

  confirmationValidateToken(token: string): Observable<string> {
    return this.http
      .post<any>(`${this.authURL}/customer-confirmation-validate-token`, {
        token
      })
      .pipe(
        catchError(error => throwError(new Error("Unexpected error occured!")))
      )
      .pipe<any>(
        map(response => {
          if (response && response.success === true) {
            localStorage.setItem(
              "oot_user_token",
              JSON.stringify(response.user)
            );

            this.currentUser = response.user;

            return response.user;
          } else {
            throw new Error(response.error);
          }
        })
      );
  }

  sendConfirmation(email: string): Observable<boolean> {
    return this.http
      .post<any>(`${this.authURL}/resend-confirmation`, { email })
      .pipe(
        catchError(error => throwError(new Error("Unexpected error occured!")))
      )
      .pipe<any>(
        map(response => {
          if (response && response.success === true) {
            return true;
          } else {
            throw new Error(response.error);
          }
        })
      );
  }

  handleError(error: any): void {
    throw new Error("Unexpected error occured!");
  }
}
