import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { environment } from "../../environments/environment";

@Injectable()
export class FAQService {
  private baseURL: string = environment.baseURL + `/api/faq`;

  constructor(private http: HttpClient) {}

  get() {
    return this.http.get(this.baseURL);
  }
}
