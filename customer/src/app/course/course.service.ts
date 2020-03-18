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

  findBySlug(slug: string, userId: string) {
    return this.http.get(this.baseURL + "/find/" + userId + "/" + slug);
  }

  submitNewCourse(course: any) {
    return this.http.post(this.baseURL + "/new", {
      course
    });
  }

  getInstructors() {
    return this.http.get(this.baseURL + "/instructors");
  }
}
