import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { environment } from "../../environments/environment";
import { IUserInfo } from "../services/login.service";

@Injectable()
export class AccountService {
  private baseURL: string = environment.baseURL + `/api/customer`;

  constructor(private http: HttpClient) {}

  get() {
    return this.http.get(this.baseURL);
  }

  saveUserData(userInfo: IUserInfo) {
    return this.http.put(this.baseURL + "/customer", { user: userInfo });
  }
}
