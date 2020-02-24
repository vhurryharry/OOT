import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { environment } from "../../environments/environment";

@Injectable()
export class HomeService {
  private baseURL: string = environment.baseURL + `/api`;

  constructor(private http: HttpClient) {}

  getTestimonials() {
    return this.http.get(this.baseURL + "/course_testimonial/home");
  }
}
