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
    return this.http.put(this.baseURL + "/customer", {
      user: {
        ...userInfo,
        avatar: ""
      }
    });
  }

  saveUserAvatar(newAvatar: File, userInfo: IUserInfo) {
    const formData = new FormData();
    formData.append("avatar", newAvatar);

    return this.http.post(this.baseURL + "/avatar/" + userInfo.id, formData);
  }

  changePassword(login: string, oldPassword: string, newPassword: string) {
    return this.http.put(this.baseURL + "/password", {
      user: {
        login,
        oldPassword,
        newPassword
      }
    });
  }
}
