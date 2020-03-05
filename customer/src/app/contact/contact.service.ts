import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { environment } from "../../environments/environment";

@Injectable()
export class ContactService {
  private baseURL: string = environment.baseURL + `/api`;

  constructor(private http: HttpClient) {}

  getNextCourseDate() {
    return this.http.get(this.baseURL + "/course/next-course-date");
  }
}
