import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { environment } from "../../environments/environment";

@Injectable()
export class CourseService {
  private baseURL: string = environment.baseURL + `/api/course`;

  constructor(private http: HttpClient) {}

  get() {
    return this.http.get(this.baseURL);
  }

  findBySlug(slug) {
    return this.http.get(this.baseURL + "/" + slug);
  }
}