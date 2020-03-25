import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { environment } from "../../environments/environment";

@Injectable()
export class SurveyService {
  private baseURL: string = environment.baseURL + `/api/survey`;

  constructor(private http: HttpClient) {}

  getQuestions(slug: string) {
    return this.http.get(this.baseURL + "/questions/find/" + slug);
  }

  submitResults(slug: string, userId: string, results) {
    return this.http.post(this.baseURL + "/results/submit/" + slug, {
      userId,
      results
    });
  }
}
