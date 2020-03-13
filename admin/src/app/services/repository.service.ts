import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { environment } from "../../environments/environment";

@Injectable({
  providedIn: "root"
})
export class RepositoryService {
  private baseURL: string = environment.baseURL + `/api/`;

  constructor(private http: HttpClient) {}

  fetch(module, state) {
    return this.http.post(this.baseURL + module + "/search", { state });
  }

  find(module, id) {
    return this.http.post(this.baseURL + module + "/find", { id });
  }

  custom(module, id, func) {
    return this.http.post(this.baseURL + module + "/" + func, { id });
  }

  uploadFile(module, file: File, id) {
    const formData = new FormData();
    formData.append("file", file);

    return this.http.post(this.baseURL + module + "/file/" + id, formData);
  }

  create(module, data) {
    return this.http.post(this.baseURL + module + "/create", data);
  }

  update(module, data) {
    return this.http.post(this.baseURL + module + "/update", data);
  }

  delete(module, ids) {
    return this.http.post(this.baseURL + module + "/delete", { ids });
  }

  restore(module, ids) {
    return this.http.post(this.baseURL + module + "/restore", { ids });
  }

  export(module, ids) {
    return this.http.post(this.baseURL + module + "/export", { ids });
  }

  move(module, id, type) {
    return this.http.post(this.baseURL + module + "/move", { type, id });
  }

  attach(type, payload) {
    return this.http.post(this.baseURL + "attach/" + type, payload);
  }

  detach(type, payload) {
    return this.http.post(this.baseURL + "detach/" + type, payload);
  }

  getPayment(paymentId) {
    return this.http.post(this.baseURL + "order/" + paymentId + "/payment", {});
  }

  refund(paymentId) {
    return this.http.post(this.baseURL + "order/" + paymentId + "/refund", {});
  }
}
