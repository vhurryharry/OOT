import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { environment } from "../../environments/environment";

@Injectable()
export class SurveyService {
  private baseURL: string = environment.baseURL + `/api/survey/questions`;

  constructor(private http: HttpClient) {}

  getQuestions(slug: string) {
    return this.http.get(this.baseURL + "/find/" + slug);
  }
}
