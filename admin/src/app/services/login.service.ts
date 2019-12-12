import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

import { environment } from '../../environments/environment';

@Injectable()
export class LoginService {
  private authURL: string = `${environment.baseURL}/api/admin/login`;

  constructor(private http: HttpClient) {}

  isLoggedIn(): boolean {
    return false;
  }
}
