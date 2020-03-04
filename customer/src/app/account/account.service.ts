import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { environment } from "../../environments/environment";
import { IUserInfo } from "../services/login.service";

export interface IPaymentMethod {
  userName: string;
  last4: string;
  brand: string;
  expMonth: number;
  expYear: number;
}

export const paymentIcons = {
  Visa: 1,
  MasterCard: 2,
  Maestro: 3,
  Cirrus: 4,
  PayPal: 5,
  "Western Union": 6,
  "American Express": 22
};

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

  getMyCourses(userId: string) {
    return this.http.get(this.baseURL + "/my-courses/" + userId);
  }

  addPaymentMethod(userId: string, token: string) {
    return this.http.post(this.baseURL + "/payment-method/" + userId, {
      token
    });
  }

  getPaymentMethod(userId: string) {
    return this.http.get(this.baseURL + "/payment-method/" + userId);
  }

  getPaymentIcon(brand: string) {
    return "/assets/images/payment/dark/" + paymentIcons[brand] + ".png";
  }
}
