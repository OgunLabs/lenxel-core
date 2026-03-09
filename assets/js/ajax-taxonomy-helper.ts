/* 
 * LENXEL CORE PLUGIN - AJAX Taxonomy Solution
 * Location: /wp-content/plugins/lenxel-core/assets/js/ajax-taxonomy-helper.ts
 * 
 * This is the TypeScript/JavaScript helper for AJAX-based taxonomy creation
 * that works with the WordPress AJAX endpoints in the Lenxel Core plugin.
 */

// =====================================================
// AJAX TAXONOMY HELPER FOR LENXEL CORE PLUGIN
// =====================================================

export interface AjaxResponse<T = any> {
  success: boolean;
  data: T;
}

export interface CategoryData {
  name: string;
  description?: string;
  parent?: number;
}

export interface TagData {
  name: string;
  description?: string;
}

export interface CreatedTerm {
  id: number;
  name: string;
  description: string;
  count: number;
  parent?: number;
  slug: string;
  taxonomy?: string;
}

// Get nonce from multiple sources for maximum reliability
const getNonce = (): string | null => {
  // Try meta tag first (most reliable)
  const metaNonce = document.querySelector('meta[name="wp-rest-nonce"]')?.getAttribute('content');
  if (metaNonce) return metaNonce;
  
  // Try existing tutorConfig
  if (window.tutorConfig?.wp_rest_nonce) {
    return window.tutorConfig.wp_rest_nonce;
  }
  
  // Try our custom lenxelRestApi (from lenxel-core plugin)
  if (window.lenxelRestApi?.nonce) {
    return window.lenxelRestApi.nonce;
  }
  
  return null;
};

// Get site URL for AJAX requests
const getSiteUrl = (): string => {
  return window.tutorConfig?.site_url || window.lenxelRestApi?.site_url || '';
};

// Generic AJAX function that works with Lenxel Core plugin endpoints
const makeAjaxRequest = async <T>(action: string, data: Record<string, any>): Promise<T> => {
  const formData = new FormData();
  formData.append('action', action);
  
  // Add all data fields
  Object.keys(data).forEach(key => {
    formData.append(key, String(data[key]));
  });
  
  // Add nonce for security
  const nonce = getNonce();
  if (nonce) {
    formData.append('nonce', nonce);
  }
  
  const siteUrl = getSiteUrl();
  const ajaxUrl = siteUrl ? `${siteUrl}/wp-admin/admin-ajax.php` : '/wp-admin/admin-ajax.php';
  
  try {
    console.log('Lenxel AJAX request:', { action, url: ajaxUrl, nonce: nonce ? 'Present' : 'Missing' });
    
    const response = await fetch(ajaxUrl, {
      method: 'POST',
      credentials: 'same-origin', // Uses WordPress cookies for authentication
      body: formData,
    });
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const result: AjaxResponse<T> = await response.json();
    
    console.log('Lenxel AJAX response:', result);
    
    if (result.success) {
      return result.data;
    } else {
      throw new Error(result.data || 'Request failed');
    }
  } catch (error) {
    console.error('Lenxel AJAX request failed:', error);
    throw error;
  }
};

// =====================================================
// LENXEL CORE TAXONOMY FUNCTIONS
// =====================================================

export const createCategoryAjax = async (categoryData: CategoryData): Promise<CreatedTerm> => {
  try {
    console.log('Creating category via Lenxel Core AJAX:', categoryData);
    
    const result = await makeAjaxRequest<CreatedTerm>('lenxel_create_course_category', {
      name: categoryData.name,
      description: categoryData.description || '',
      parent: categoryData.parent || 0,
    });
    
    console.log('Category created successfully via Lenxel Core:', result);
    return result;
  } catch (error: any) {
    console.error('Lenxel category creation failed:', error);
    
    // Provide specific error messages
    if (error.message.includes('nonce')) {
      throw new Error('Security verification failed. Please refresh the page and try again.');
    } else if (error.message.includes('permission')) {
      throw new Error('You do not have permission to create categories.');
    } else if (error.message.includes('logged')) {
      throw new Error('You are not logged in. Please refresh the page and try again.');
    } else {
      throw new Error(error.message || 'Failed to create category. Please try again.');
    }
  }
};

export const createTagAjax = async (tagData: TagData): Promise<CreatedTerm> => {
  try {
    console.log('Creating tag via Lenxel Core AJAX:', tagData);
    
    const result = await makeAjaxRequest<CreatedTerm>('lenxel_create_course_tag', {
      name: tagData.name,
      description: tagData.description || '',
    });
    
    console.log('Tag created successfully via Lenxel Core:', result);
    return result;
  } catch (error: any) {
    console.error('Lenxel tag creation failed:', error);
    
    if (error.message.includes('nonce')) {
      throw new Error('Security verification failed. Please refresh the page and try again.');
    } else if (error.message.includes('permission')) {
      throw new Error('You do not have permission to create tags.');
    } else if (error.message.includes('logged')) {
      throw new Error('You are not logged in. Please refresh the page and try again.');
    } else {
      throw new Error(error.message || 'Failed to create tag. Please try again.');
    }
  }
};

// =====================================================
// LENXEL CORE DEBUG UTILITIES
// =====================================================

// Test authentication with Lenxel Core plugin endpoints
export const testLenxelAuthentication = async (): Promise<void> => {
  try {
    console.log('Testing Lenxel Core authentication...');
    console.log('Available nonce:', getNonce());
    console.log('Site URL:', getSiteUrl());
    
    const result = await makeAjaxRequest('lenxel_debug_auth', {});
    console.log('Lenxel authentication test result:', result);
  } catch (error) {
    console.error('Lenxel authentication test failed:', error);
  }
};

// TypeScript declarations for Lenxel Core plugin
declare global {
  interface Window {
    tutorConfig?: {
      wp_rest_url: string;
      wp_rest_nonce: string;
      site_url: string;
    };
    lenxelRestApi?: {
      root: string;
      nonce: string;
      current_user_id: number;
      site_url: string;
    };
  }
}

export {};